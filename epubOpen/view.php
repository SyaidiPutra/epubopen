<!doctype html>
<html lang="<?= $catalog['language'] ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="<?= $this->DirTool('css\reading.css') ?>">
    <link rel="stylesheet" href="<?= $this->DirTool('css\loader.css') ?>">
    <title>Reading Now :: <?= $catalog['title'] ?></title>

</head>

<body>
    <div id="loadingPage">
        <div class="containerLoader">
            <div class="loading"><i></i><i></i><i></i><i></i></div>
        </div>
    </div>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="overflow-auto">
            <div class="sidebar-header">
                <h3>Read Tool</h3>
                <button type="button" class="btn btn-close rounded-circle text-white btn-in-Sidebar sidebarCollapse btn-light">
                </button>
            </div>

            <ul class="list-unstyled mt-3">
                <p><?= (empty($catalog['title']))? 'uknown' : $catalog['title'] ?></p>
                <li>
                    <a id="contentsBtnTool" class="text-decoration-none">Contents</a>
                </li>
                <li>
                    <a id="detailBtnTool" class="text-decoration-none">Details</a>
                </li>
            </ul>
            <hr class="space-sidebar">
            <div class="tool-kit">
                <div class="form-box">
                    <label for="fontSize" class="form-label">Font Size</label>
                    <small class="value" id="displaySizeFont">12px</small>
                    <input type="range" class="form-range" min="12" max="64" value="12" id="fontSize">
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Mode</label>
                    <!-- <br> -->
                    <div class="btn-group w-100" role="group" aria-label="Basic example">
                        <input type="radio" class="btn-check" value="0" id="lightMode" name="modeDispaly"
                            autocomplete="off" checked>
                        <label class="btn btn-outline-light" for="lightMode">Light</label>
                        <input type="radio" class="btn-check" value="1" id="darkMode" name="modeDispaly"
                            autocomplete="off">
                        <label class="btn btn-outline-dark" for="darkMode">Dark</label>
                    </div>
                </div>
                <div class="mb-3">
                    <input type="checkbox" class="btn-check" id="blueFilerToggel" autocomplete="off">
                    <label class="btn btn-outline-warning w-100" for="blueFilerToggel">Filter Eye</label><br>
                </div>
                <div class="mb-3">
                    <div id="googleTranslate"></div>
                </div>
            </div>
            <div class="px-3">
               <div class="card">
                   <div class="card-body text-dark">
                       <p class="fw-bold mb-0">Epub Open! v.0.1</p>
                       <p>Syaidi Putra <br> <small>Developer</small></p>
                       <a href="#" class="text-decoration-none">GitHub</a>
                   </div>
               </div>
            </div>
        </nav>


        <div id="filderBlue"></div>
        <div id="protectedScreen"></div>

        <!-- toggel -->
        <button type="button" class="btn btn-tool sidebarCollapse">
            <i class="fas fa-align-left"></i>
            <span>Tool</span>
        </button>
    </div>


    <div id="bookContent" oncopy="alert('Copying forbidden!');return false"  unselectable="on" onselectstart="return false;"  onmousedown="return false;">
        <div class="container">
            <?php foreach($book['page'] as $page): ?>
            <?php 
                    $doc = new DOMDocument();
                    $doc->load($page);
                    $ext = explode('.', $page);
                    $ext = end($ext);
                    if($ext == 'xml'){
                        echo $doc->saveXML();
                    }elseif($ext == 'html'){
                        echo $doc->saveHTML();
                    }else{
                        echo 'File Not Support';
                    }
                    ?>
            <?php endforeach; ?>
        </div>
    </div>


    <!-- module -->
    <!-- detail -->
    <div class="modal fade" id="datailmodel" tabindex="-1" aria-labelledby="datailmodelLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="datailmodelLabel">Detail Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="font-size: 1rem;">
                    <!-- <?php var_dump($catalog) ?> -->
                    <table class="table">
                        <tbody>
                            <?php foreach($catalog as $item => $val): ?>
                            <tr>
                                <th><?= $item ?></th>
                                <td><?php

                                    if(!empty($val)){
                                        if(is_array($val)){
                                            foreach($val as $sub){
                                                echo $sub . '<br>';
                                            }
                                        }else{
                                            echo $val;
                                        }
                                    }else{
                                        echo 'Empty';
                                    }
                                
                                ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- contents -->
    <div class="modal fade" id="contentsModal" tabindex="-1" aria-labelledby="contentsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contentsModalLabel">Contents Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" >
                <ul id="listContents" class="listContents">
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    <!-- footrwe Js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <script>
        function setCookie(name,value,days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days*24*60*60*1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "")  + expires + "; path=/";
        }
        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for(var i=0;i < ca.length;i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1,c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
            }
            return null;
        }
    </script>

    <script>
        // font size
        var fontSize = getCookie('viewBookFontSize');
        $('#displaySizeFont').html(fontSize)
        $('#bookContent').css('fontSize', fontSize)
        $('#fontSize').val(fontSize)

        // drakMode
        var mode = getCookie('viewBookDrakMode');
        var Room = $('#bookContent')
        if (mode == '1') {
                Room.addClass('drak-mode')
                $('#darkMode')attr('checked', true)
            } else {
                $('#lightMode')attr('checked', true)
                Room.removeClass('drak-mode')
            }
        
        // filterBlue
        var blue = getCookie('viewBookFilterBlue');
        if(blue == '1'){
            $('#filderBlue').toggleClass('active')
        }
    </script>

    <script>
        var img = $('img')
        <?php if(isset($img)): ?>
        var dataBase64 = <?= $img ?> ;
        var imgSrc = '<?= json_encode($book['img']) ?>'
        for (let index = 0; index < img.length; index++) {
            const src = img[index].getAttribute('src')
            $("img[src='" + src + "']").attr('src', dataBase64[src])
        }
        <?php  else: ?>
            for (let index = 0; index < img.length; index++) {
                const src = img[index].getAttribute('src')
                $.post('<?= $url ?>', {path: '<?= $book['baseUrl'] ?>', file: src}, function(data){
                    $("img[src='" + src + "']").attr('src',data)
                })
            }
        <?php  endif; ?>
    </script>

    <!-- celar -->
    <script>
        $('body meta').remove()
        $('body link').remove()
        $('body title').remove()
        $("img[alt='Cover']").removeAttr('style').addClass('cover')
    </script>
    
    <!-- Tool -->
    <script>
        $(document).ready(function () {
            $('.sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
            $('#sidebar').toggleClass('active');
        });

        // font Size
        $('#fontSize').mousemove(function () {
            var size = $(this).val() + 'px'
            $('#displaySizeFont').html(size)
            $('#bookContent').css('fontSize', size)
            setCookie("viewBookFontSize",size,30);
        })

        // mode Display
        $("input[name='modeDispaly']").change(function () {
            $("input[name='modeDispaly']").removeAttr('checked')
            $(this).attr('checked', true);
            var Room = $('#bookContent')
            var mode = $(this).val()
            if (mode == '1') {
                Room.addClass('drak-mode')
            } else {
                Room.removeClass('drak-mode')
            }
            setCookie("viewBookDrakMode",mode,30);
        })

        // blue Filter
        $('#blueFilerToggel').change(function () {
            $('#filderBlue').toggleClass('active')
            var toggel = $('#filderBlue.active').length
            setCookie("viewBookFilterBlue",mode,30);
        })

        //color
        $("#bookContent .container p").css({ 'color' : '' });
        $("#bookContent .container p span").css({ 'color' : '' });
    </script>

    <script>
        // modal
        //detail
        $('#detailBtnTool').click(function(){
            $('#datailmodel').modal('toggle')
        })
        //contents
        $('#contentsBtnTool').click(function(){
            $('#contentsModal').modal('toggle')
        })

        //labeling Conten Header
        var h1 = $('h1')
        var h2 = $('h2')

        for (let index = 0; index < h1.length; index++) {
            h1[index].classList.add('headerBook')
        }
        for (let index = 0; index < h2.length; index++) {
            h2[index].classList.add('headerBook')
        }

        var getAllHeader = $('.headerBook')
        console.log(getAllHeader);
        for (let index = 0; index < getAllHeader.length; index++) {
            const element = getAllHeader[index].localName;
            var randNo = Math.floor(Math.random() * 1000991);
            getAllHeader[index].setAttribute('id', 'hedaer'+randNo);
            var nameHeader = getAllHeader[index].outerText
            if(element == 'h1'){
                $('#listContents').append(`
                    <li>
                        <a href="#hedaer`+randNo+`" onclick="contentSelected()" class="content-h1">`+nameHeader+`</a>
                    </li>
                `)
            }else{
                $('#listContents').append(`
                    <li>
                        <a href="#hedaer`+randNo+`" onclick="contentSelected()" class="content-h2">`+nameHeader+`</a>
                    </li>
                `)
            }
        }

        function contentSelected() {
            $('#contentsModal').modal('toggle')
            $('#sidebar').toggleClass('active');
        }



    </script>
    
    <script>
        $(document).ready(function () {
            setTimeout(() => {
                $('#loadingPage').remove()
            }, 300);
        });
    </script>



    
    <script type="text/javascript">
        // init
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({pageLanguage: '<?= $catalog['language'] ?>'}, 'googleTranslate');
        }

        //nav kill
       setInterval(() => {
           var boy = $('body')
           if(boy.attr('style')){
               boy.removeAttr("style")
           }
       }, 500);

    </script>

    <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>




</body>

</html>