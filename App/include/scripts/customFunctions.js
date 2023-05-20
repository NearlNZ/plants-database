//Dynamic add active to sidebar
function activeSidebar(){
    let pageUrl = "";

    if ($('.active-menu-url').length) {
        pageUrl = $('.active-menu-url').text();
    }

    if(pageUrl !== ''){
        //Remove .php extension
        if(pageUrl.indexOf('.php') !== -1){
            pageUrl = pageUrl.replace('.php', '');
        }

        //Add the "active" classe to the menu item with a matching URL
        $('.menu-item').each(function () {
            let menuItemUrl = $(this).find('a').attr('href');

            if (menuItemUrl === pageUrl) {
                $(this).addClass('active');

                //If the menu item is a sub-menu, add the "active" and "open" classes to the "havesub" element
                let parentSubMenu = $(this).parent('.menu-sub');
                if (parentSubMenu.length) {
                    parentHavesub = parentSubMenu.closest('.havesub').addClass('active open');
                }
            }
        });
    }
}
$(document).ready(function () {
    activeSidebar()
});

//Ajax Request
function ajaxRequest(option){
    const{
        type = 'POST',
        url = '',
        data = {},
        errorUrl = '../requestError',
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

//Back button
function goBack() {
    if (window.history.length > 1) {
        window.history.back();
    } else {
        window.location.href = '../index.php';
    }
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
// function printContent(contentContainer) {
// 	let content = contentContainer.html();

// 	printWindow = window.open("");
// 	printWindow.document.write('<link rel="stylesheet" href="../assets/font/Kanit.css"/>');
//     printWindow.document.write('<link rel="stylesheet" href="../assets/css/template.css"/>');
// 	printWindow.document.write('<script src="../assets/js/bootstrap.min.js"></script>');
// 	printWindow.document.write('<link rel="stylesheet" href="../assets/css/custom-style.css"/>');
    
// 	printWindow.document.write('<script src="../assets/vendor/fontawesome/js/all.min.js"></script>');
//     printWindow.document.write('<script src="../assets/vendor/perfect-scrollbar/perfect-scrollbar.js"></script>');
// 	printWindow.document.write('<STYLE type="text/css">body {font-family: "Kanit";} td {font-size: 12px;} th {font-size: 13px !important;}</STYLE>');
// 	printWindow.document.write('<STYLE media="print">.not-print {display: none;} tr, td, th {padding: 10 !important;}</STYLE>');
// 	printWindow.document.write('<img src="../assets/img/element/comsci-logo.png" class="border me-2 mb-2" style="width:50px;"></img><span class="h4">ระบบฐานข้อมูลพืช สาขาวิทยาการคอมพิวเตอร์</span><br><br>');
	
//     printWindow.document.write(content);
	
// 	setTimeout(function(){
// 		printWindow.print();
// 		printWindow.close();
// 	}, 100);
	
// }

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
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonColor: '#fff',
        cancelButtonText: '<span class="text-black">' + cancelButtonText + '</span>',
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