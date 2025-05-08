$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    });
})


function custom_toast(mode,text){
    let html = ``;
    if(mode == 'success'){
        html = `
            <div class=" bg-light py-2 px-3 mb-2 rounded shadow-sm overflow-hidden toast_div">
                <p class="m-0 text-success"><i class="fas fa-check-double"></i> Success</p>
                <small class="m-0 p-0">${text}</small>
            </div>`;
    }

    if(mode == 'error'){
        html = `
            <div class=" bg-light py-2 px-3 mb-2 rounded shadow-sm overflow-hidden toast_div">
                <p class="m-0 text-danger"><i class="fas fa-exclamation-circle"></i> Error</p>
                <small class="m-0 p-0">${text}</small>
            </div>`;
    }

    if(mode == 'cancel'){
        html = `
            <div class=" bg-light py-2 px-3 mb-2 rounded shadow-sm overflow-hidden toast_div">
                <small class="m-0 p-0"><i class="fa fa-ban"></i> Cancelled action</small>
            </div>`;
    }

    let $toast = $(html);
    $(".custom_toast").append($toast);
    
    $toast.css("animation", "toastAnimation 0.2s ease-out forwards");
    
    setTimeout(() => {
        $toast.css("animation", "toastAnimationExit 0.5s ease-out forwards");
        $toast.on("animationend", function () {
            $(this).remove();
        });
    }, 3000);

}

function getBadgeStatus(status){
    let html = ``;
    if(status == 'done'){
        html = `<div class="position-relative badge bg-success" style="width:100px;">Done</div>`;
    }
    if(status=='to-do'){
        html = `<div class="position-relative badge bg-warning" style="width:100px;">To do</div>`;
    }
    if(status == 'in-progress'){
        html = `<div class="position-relative badge bg-primary" style="width:100px;">In Progress</div>`;
    }
    return html;
}