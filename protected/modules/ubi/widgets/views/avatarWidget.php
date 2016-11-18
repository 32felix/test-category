<?
\tit\utils\assets\FileApiAsset::register($this);
?>

<div id="userpic" class="userpic">
    <img style="position: absolute;" class="ubi-avatar-defoult-img" src="/media/user/<?=$model->id?>/<?=dechex(strtotime($model->timeAddAvatar))?>_210x280.jpeg">
    <div class="js-preview userpic__preview">
    </div>
    <div class="btn btn-success js-fileapi-wrapper">
        <div class="js-browse view-upload-avatar">
            <span class="btn-txt">Choose</span>
            <input type="file" id="upload-avatar" class="" name="filedata">
        </div>
        <div class="js-upload" style="display: none;">
            <div class="progress progress-success"><div class="js-progress bar"></div></div>
            <span class="btn-txt">Uploading</span>
        </div>
    </div>
</div>

<div id="popup" class="popup" style="display: none;">
    <div class="popup__body"><div class="js-img"></div></div>
    <div style="margin: 0 0 5px; text-align: center;">
        <div class="js-upload btn btn_browse btn_browse_small">Upload</div>
    </div>
</div>
<script>

    $(function(){
        $('#userpic').fileapi({
            url: '/ubi/user/getAvatar',
            accept: 'image/*',
            imageSize: { minWidth: 210, minHeight: 280 },
            elements: {
                active: { show: '.js-upload', hide: '.js-browse' },
                preview: {
                    el: '.js-preview',
                    width: 210,
                    height: 280
                },
                progress: '.js-progress'
            },
            onSelect: function (evt, ui)
            {
                var file = ui.files[0];
                if( !FileAPI.support.transform ) {
                    alert('Your browser does not support Flash :(');
                }
                else if( file ){
                    $('#popup').modal({
                        closeOnEsc: true,
                        closeOnOverlayClick: false,
                        onOpen: function (overlay){
                            $(overlay).on('click', '.js-upload', function (){
                                $.modal().close();
                                $('#userpic').fileapi('upload');
                            });
                            $('.js-img', overlay).cropper({
                                file: file,
                                bgColor: '#fff',
                                maxSize: [$(window).width()-100, $(window).height()-100],
                                minSize: [210, 280],
                                selection: '90%',
                                onSelect: function (coords){
                                    $('#userpic').fileapi('crop', file, coords);
                                }
                            });
                        }
                    }).open();
                }
            }
        });
    });
</script>