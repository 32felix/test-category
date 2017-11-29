<?php

namespace app\components\utils;
use app\models\Category;

class CategoryUtils
{
    public static function deleteChildren($parentId)
    {
        $categories = Category::findAll(['parentId' => $parentId]);

        if ($categories)
            foreach ($categories as $category)
            {
                $category->deleted = 1;
                if ($category->save())
                    self::deleteChildren($category->id);
            }
    }

    public static function listItems($parentId=0)
    {
        $text = '';
        $categories = Category::findAll(['parentId' => $parentId, 'deleted' => 0]);

        if ($categories)
        {
            $text = '<table class="table table-striped table-bordered"><thead><tr><th>ID</th><th>Название</th>
                                    <th>Ссылка</th><th></th></tr></thead><tbody>';
            foreach ($categories as $category)
            {
                $add = '';
                $children = self::listItems($category->id);

                if ($children != '')
                    $add = '<span id="add-text" class="glyphicon glyphicon-plus-sign">';

                $text.="<tr>
                            <td>$add $category->id</td>
                            <td>$category->name</td>
                            <td>$category->link</td>
                            <td>
                                <a href='/site/create?id=$category->id'><span class='glyphicon glyphicon-plus'></span></a>
                                <a href='/site/update?id=$category->id' title='Оновити' aria-label='Оновити' data-pjax='0'>
                                    <span class='glyphicon glyphicon-pencil'></span>
                                </a>
                                <a href='/site/delete?id=$category->id' title='Видалити' aria-label='Видалити' data-pjax='0'
                                        data-confirm='Ви впевнені, що хочете видалити цей елемент?' data-method='post'>
                                    <span class='glyphicon glyphicon-trash'></span>
                                </a>
                            </td>
                        </tr>";

                if ($children != '')
                    $text.="<tr class='children hidden'><td colspan='4'>$children</td></tr>";
            }
            $text.='</tbody></table>';
        }

        return $text;
    }

}