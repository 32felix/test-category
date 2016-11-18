<?php

namespace app\controllers;

use app\components\utils\ImageUtils;
use app\models\Images;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Yii;
use yii\imagine\Image;
use yii\web\Controller;
use yii\web\HttpException;

class ImageController extends Controller
{
    public function actionCreateImage()
    {
        $idPost = false;
        $ownerType = false;
        $model = false;
        $res = [];

        if (!Yii::$app->request->isAjax)
            return false;

        if(isset($_POST['id']) && !empty($_POST['id'])) $idPost = $_POST['id'];

        if(isset($_POST['type']) && !empty($_POST['type'])) $ownerType = $_POST['type'];

        if(isset($_POST['w']) && !empty($_POST['w'])) $w = $_POST['w'];
        if(isset($_POST['h']) && !empty($_POST['h'])) $h = $_POST['h'];

        if(isset($_FILES) && !empty($_FILES))
        {
            $files  = $_FILES;
            foreach ($files as $file)
            {
                if($file['type'] != 'image/jpeg' && $file['type'] != 'image/png')
                {
                    $res['error'] = 'Неправильне розширення зображення! Воно повинно бути .jpg або .png';
                    break;
                }

                if ($idPost)
                    $model = Images::findOne(['productId' => $idPost, 'productType' => $ownerType]);

                if (!$model)
                {
                    $model = new Images();
                    if ($idPost)
                        $model->productId = $idPost;
                    if ($ownerType)
                        $model->productType = $ownerType;
                }
                if($file['type'] == 'image/jpeg')
                    $model->ext = 'jpg';
                else
                    $model->ext = 'png';
                $model->timeUpdate = date('Y-m-d H:i',time());
                if($model->save())
                {
                    $imagesPath = Yii::$app->basePath.'/../res'.ImageUtils::resourceIdToDirPass($model->id);
                    if (!is_dir($imagesPath))
                        mkdir($imagesPath, 0777, true);
                    $nameAvatar=$model->id.".".$model->ext;
                    $name = $imagesPath . '/' . $nameAvatar;
                    move_uploaded_file($file['tmp_name'],$name);
                }
            }
        }

        if (empty($res))
        {
            $name = ImageUtils::genImageUrl($model->id, $model->timeUpdate, $w, $h);

            header("Content-type: application/json");
            echo json_encode(['id' => $model->id, 'src' => $name, 'error' => 'none'], JSON_UNESCAPED_UNICODE);
            Yii::$app->end();
        }
        else
        {
            header("Content-type: application/json");
            echo json_encode(['error' => $res['error']], JSON_UNESCAPED_UNICODE);
            Yii::$app->end();
        }

    }


    public function actionImage($id, $time, $slug=null, $w=null, $h=null, $ext='jpg')
    {
        $id = str_replace("/","",$id);
        $allowedAvatarSizes = ["37x37", "66x66", "50x50", "100x100", "150x200", "160x160","170x170","210x280", "300x200",
            "200x130", "300x185", "309x192", "630x384", "630x370", "1323x343","600","300","620","470x246","185x115","x620"];

        if (($w || $h)
            &&
            (
                $h!=null && !in_array("{$w}x{$h}", $allowedAvatarSizes)
                ||
                $h==null && !in_array("{$w}", $allowedAvatarSizes)
                ||
                $w==null && !in_array("x{$h}", $allowedAvatarSizes)
            )
        )
            throw new HttpException(404, "Image size not found");

//        $woteMark = !in_array("{$w}x{$h}", ["300x200", "1323x343", "630x370", "66x66", "37x37", "100x100"]);
        $woteMark = in_array("{$w}", ["620","300"]) && is_null($h)
            || in_array("{$w}x{$h}", ["470x246"]) || (!$w && !$h);
        ;
//        if (!)
//            $woteMark = true;

        $modelResource = Images::findOne(['id'=>$id]);

        if (empty($modelResource)) throw new HttpException(404, 'Image not load');

        $idPath = ImageUtils::resourceIdToDirPass($id);
        $time2 = base_convert(strtotime($modelResource->timeUpdate),10,36);
        $slug2=empty($modelResource->slug)?$modelResource->id:$modelResource->slug;

        if (!$w && !$h)
            $imageName = "{$slug2}.{$time2}.{$ext}";
        else
            $imageName = "{$slug2}.{$time2}." . ($w?"{$w}":"") . ($h?"x{$h}":"") . ".{$ext}";

        $imagesPath = Yii::$app->basePath.'/../media/res'. $idPath."/$imageName";

        if ($slug!=$slug2 || $time!=$time2)
        {
            $id2=trim(preg_replace("//","/",$id),"/");
            header("Location: /media/res/$id2/$imageName");
            die;
        }

        #print $imagesPath."<br>";

        if ( ! file_exists($imagesPath))
        {

            $imageSource = Yii::$app->basePath.'/../res'. $idPath .'/'. "$id.{$ext}";
            if (!file_exists($imageSource))
                throw new HttpException(404);

            $img = (new Imagine)->open($imageSource);

            if(!file_exists(dirname($imagesPath)))
                mkdir(dirname($imagesPath),0775, true);

            $box = $img->getSize();


            if (!$w && $h)
                $w = $h / $box->getHeight() * $box->getWidth();


            if (!$w && $box->getWidth()>1600){
                $s = 1600/$box->getWidth();
                $w = $box->getWidth()*$s;
                $h = $box->getHeight()*$s;
            }

            if ($w) {
                if ($h == null)
                    $h = $w / $box->getWidth() * $box->getHeight();

                $boxRatio = $box->getWidth() / $box->getHeight();
                $ratio = $w / $h;
                if ($boxRatio > $ratio + 1e-8) {
                    $nw = $ratio * $box->getHeight();
                    $img->crop(new Point(max(0, ($box->getWidth() - $nw) / 2), 0), new Box($nw, $box->getHeight()));
                } else if ($boxRatio < $ratio - 1e-8) {
                    $nh = 1 / $ratio * $box->getWidth();
                    $img->crop(new Point(0, max(0, ($box->getHeight() - $nh) / 2)), new Box($box->getWidth(), $nh));
                }

                $img->resize(new Box($w, $h));
            }


            if ($woteMark)
            {
                $logo = Image::getImagine()->open(\Yii::$app->basePath."/../images/watermark.png");

                if (!$w){
                    $box = $img->getSize();
                    $w = $box->getWidth();
                    $h = $box->getHeight();
                }

                $ls=$logo->getSize();

                $scale = min(0.27*$w/$ls->getWidth(), 0.27*$h/$ls->getHeight() );
                $scale = min($scale, 1.2);

                $logo->resize(new Box($ls->getWidth()*$scale, $ls->getHeight()*$scale));
                $ls=$logo->getSize();

                $img->paste($logo, new Point($w-$ls->getWidth()-5,$h-$ls->getHeight()-5));
            }

            $img->save($imagesPath,['quality'=>85]);

        }
        if ($ext == 'jpg')
            header("Content-Type: image/jpeg");
        else
            header("Content-Type: image/png");

        echo file_get_contents($imagesPath);
    }


}
