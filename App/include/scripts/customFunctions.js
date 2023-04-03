//Dynamic add active to sidebar (Require jquery.min.js)
function activeSidebar(){
    var currentPage = "";
    if ($('.fix-menu').length) {
        currentPage = $('.fix-menu').text();
    }else{
        currentPage = location.pathname.split('/').pop();
    }

    //If the file name is empty, assume it's the first item if exist
    if(currentPage === ''){
        currentPage = $('.menu-item:first-child').find('a').attr('href') || "";
    }

    if(currentPage !== ''){
        //Remove .php extension
        if(currentPage.indexOf('.php') !== -1){
            currentPage = currentPage.replace('.php', '');
        }

        //Add the "active" classe to the menu item with a matching URL
        $('.menu-item').each(function () {
            var menuItemUrl = $(this).find('a').attr('href');

            if (menuItemUrl === currentPage) {
                $(this).addClass('active');

                //If the menu item is in a sub-menu, add the "active" and "open" classes to the "havesub"
                var parentSubMenu = $(this).parent('.menu-sub');
                if (parentSubMenu.length > 0) {
                    parentHavesub = parentSubMenu.closest('.havesub').addClass('active open');
                }
            }
        });
    }
}
$(document).ready(function () {
    activeSidebar()
});

//Ajax (Require jquery.min.js)
function ajaxRequest(option){
    const{
        type = 'POST',
        url = '',
        data = {},
        errorUrl = '',
        processData = true,
        contentType = 'application/x-www-form-urlencoded',
        successCallback = () => { },
        errorCallback = () => { },
        completeCallback = () => { }
    } = option;

    $.ajax({
        type: type,
        url: url,
        data: data,
        processData: processData,
        contentType: contentType,
        success: function(response){
            if (!response.status){
                showRequestError(response, errorUrl);
            }else{
                successCallback(response);
            }
        },
        error: function (response){
            showRequestError(response.responseText, errorUrl);
            errorCallback();
        },
        complete: function (){
            completeCallback();
        }
    });
}

//Logout (Require sweetalert2.all.min.js)
function logout(){
    showConfirm({
        title: 'ออกจากระบบ',
        text: 'กำลังออกจากระบบ ต้องการดำเนินการต่อหรือไม่',
        confirmCallback : function() {
            Swal.fire({
                icon: 'success',
                text: 'กำลังออกจากระบบโปรดรอสักครู่',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location.href= "../logout";
            });
        }
    });
}

//Print table content
function printReport() {
	var table = $(".table-responsive").html();
	printView = window.open("");

	printView.document.write('<link rel="stylesheet" href="../assets/font/Kanit.css"/>');
    printView.document.write('<link rel="stylesheet" href="../assets/css/template.css"/>');
	printView.document.write('<script src="../assets/js/bootstrap.min.js"></script>');
	printView.document.write('<link rel="stylesheet" href="../assets/css/custom-style.css"/>');
    
	printView.document.write('<script src="../assets/vendor/fontawesome/js/all.min.js"></script>');
	printView.document.write('<STYLE type="text/css">body {font-family: "Kanit";} td {font-size: 12px;} th {font-size: 13px !important;}</STYLE>');
	printView.document.write('<STYLE media="print">.not-print {display: none;} tr, td, th {padding: 10 !important;}</STYLE>');
	printView.document.write('<img src="../assets/img/element/logo.png" class="border me-2 mb-2" style="width:50px;"></img><span class="h4">ระบบฐานข้อมูลพืช สาขาวิทยาการคอมพิวเตอร์</span><br><br>');
	printView.document.write(table);
	
	setTimeout(function(){
		printView.print();
		printView.close();
	}, 100);
	
}

//Show confirm befor process (Require sweetalert2.all.min.js)
function showConfirm(option){
    const {
        icon = '',
        title = '',
        text = '',
        cancelButtonText = 'ยกเลิก',
        confirmButtonText = 'ยืนยัน',
        confirmCallback = () => { }
    } = option;

    Swal.fire({
        icon: icon,
        title: title,
        text: text,
        reverseButtons: true,
        showCancelButton: true,
        cancelButtonColor: '#fff',
        cancelButtonText: '<span class="text-black">' + cancelButtonText + '</span>',
        confirmButtonColor: '#696cff',
        confirmButtonText: '<span class="text-white">' + confirmButtonText + '</span>'
    }).then((result) => {
        if (result.isConfirmed){
            confirmCallback();
        }
    });
}

//Show internal error in console (Require sweetalert2.all.min.js)
function showRequestError(error, errorUrl){
    if (error.indexOf('{"status":') !== -1 && error.indexOf('}')){
        error = error.substring(0, error.indexOf('{"status":'));
    }

    Swal.fire({
        icon: 'error',
        title: 'Request Error',
        html: "An error occurred while processing",
        showConfirmButton: false,
        showCloseButton: true,
        didOpen: () => {
            $('.swal2-close').blur();
        }
    }).then(() =>{
        //Set errorLog
        localStorage.setItem('GR-localStorage-errorLog', error);

        window.open(errorUrl, '_blank');
    });
}

//Swal response from Ajax (Require sweetalert2.all.min.js)
function showResponse(option){
    const{
        response,
        timer,
        callback = () => {}
    } = option;

    const swalOption ={
        icon: response.status,
        title: response.title,
        text: response.text,
        html: response.html,
        showConfirmButton: false,
        showCloseButton: true,
        didOpen: () => {
            $('.swal2-close').blur();
        }
    };

    if (timer){
        swalOption.timer = timer;
        swalOption.timerProgressBar = true;
    }

    Swal.fire(swalOption).then(() =>{
        callback();
    });
}