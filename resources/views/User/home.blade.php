<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>
    <div class="vw-100 shadow">
        <div class="container p-2 d-flex justify-content-between ">
            <div>
                <h4 class="m-0"><i class="fa-solid fa-table"></i> Tasks Board</h4>
            </div>
            <div>
                <p class="m-0"> {{ Auth::user()->username }} <i class="fa fa-user"></i> | <a href="{{ URL('logout') }}">Logout <i class="fa-solid fa-power-off"></i></a></p>
            </div>
        </div>
    </div>

    <div class="container mt-2">
        <button class="btn primary_background text-light" data-bs-toggle="modal" data-bs-target="#newTaskModal"><i class="fa fa-plus"></i> New task</button>
    </div>


    <!--add new task modal -->
    <div class="modal fade" id="newTaskModal" tabindex="-1"  aria-hidden="true">
        <form id = "addTaskForm" enctype="multipart/form-data">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">New Task</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label>Title</label>
                            <input type="text" name="title" class="addTaskFormInput form-control form-control-sm" required placeholder="Title">
                        </div>
                        <div class="mb-2">
                            <label>Content</label>
                            <textarea name="content" id="" class="addTaskFormInput form-control form-control-sm" required ></textarea>
                        </div>
                        <div class="mb-2">
                            <input type="file" name="images[]" class="addTaskFormInput form-control form-control-sm shadow-none outline-none" multiple>
                        </div>
                        <div class="mb-2">
                            <input type="checkbox" id="save_as_draft_checkbox" class="addTaskFormInput_checkbox" name="save_as_draft_checkbox" value="1">
                            <label for="save_as_draft_checkbox"> Save as draft</label><br>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm primary_background text-light">Add</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!--add new sub task modal -->
    <div class="modal fade" id="newSubTasksModal" tabindex="-1"  aria-hidden="true">
        <form id = "addSubTaskForm" enctype="multipart/form-data">
            <input type="hidden" class="task_id_input" name="task_id" value="">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <p class="modal-title fs-6 ">Add sub task for: <br><span class="w-100 fs-3 main_task_title">Sample main title</span></p>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label>Sub task Title</label>
                            <input type="text" name="title" class="addsubTaskFormInput form-control form-control-sm" required placeholder="Title">
                        </div>
                        <div class="mb-2">
                            <label>Sub task Content</label>
                            <textarea name="content" id="" class="addsubTaskFormInput form-control form-control-sm" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm primary_background text-light">Add</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <hr>

    <!-- task list  -->
    <div class="mt-2">
        <div class="container">
            <div class="row">
                <div class="col-10">
                    <div class="d-flex justify-content-between align-items-end">
                        <h4><i class="fa fa-list"></i> Tasks list</h4>
                        <div class="d-flex align-items-end">
                            <div class="me-1 d-flex align-items-center">
                                <span class="me-2"><i class="fa fa-search"></i></span>
                                <input type="search" id="search_task" class="form-control form-control-sm" placeholder="Search task here..">
                            </div>
                            <div class="me-1">
                                <label>Sort By</label>
                                <select name="" id="page_sort_by" class="form-control form-control-sm text-center">
                                    <option value="title_asc">Title A–Z</option>
                                    <option value="title_desc">Title Z-A</option>
                                    <option value="date_asc">Date: Oldest First</option>
                                    <option value="date_desc" selected>Date: Newest First</option>
                                </select>
                            </div>
                            <div class="me-1">
                                <label>Page limit</label>
                                <select name="" id="page_limit" class="form-control form-control-sm text-center">
                                    <option>10</option>
                                    <option>20</option>
                                    <option>30</option>
                                    <option>50</option>
                                    <option>75</option>
                                    <option>100</option>
                                </select>
                            </div>
                            
                        </div>
                    </div>
                    <div class="row mt-2" id="task_list"></div>
                </div>
                <div class="col-2">
                    <p>Filter</p>
                    <div class="filter_outer">
                        <div>
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold">FILTER BY</h6>
                                <!-- <button class="btn btn-sm rounded shadow-sm primary_background text-light">
                                    <div class="d-flex align-items-center">
                                        <p class="p-0 m-0">Reset</p>
                                    </div>
                                </button> -->
                            </div>
                            <div class="mt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="m-0">To do</p>
                                    <input type="checkbox" value="1" class="filter_checkbox" id="checkbox_status_todo" checked>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="m-0">In Progress</p>
                                    <input type="checkbox" value="1" class="filter_checkbox" id="checkbox_status_inprogress" checked>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="m-0">Done</p>
                                    <input type="checkbox" value="1" class="filter_checkbox" id="checkbox_status_done" checked>
                                </div>
                            </div>
                            <hr>
                            <div class="mt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="m-0">Published</p>
                                    <input type="checkbox" value="1" class="filter_checkbox" id="checkbox_tag_published" checked>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="m-0">Drafted</p>
                                    <input type="checkbox" value="1" class="filter_checkbox" id="checkbox_tag_draft" checked>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <button class="btn btn-sm btn-light shadow viewTrashModalBtn" data-bs-toggle="modal" data-bs-target="#viewTrashModal"><i class="fa fa-trash"></i> View trash</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- task options modal -->
    <div class="modal fade" id="taskOptionsModal" tabindex="-1"  aria-hidden="true">
        <form id = "updateTaskStatus" enctype="multipart/form-data">
            <div class="modal-dialog">
                <div class="modal-content"></div>
            </div>
        </form>
    </div>

    <!-- subtask options modal -->
    <div class="modal fade" id="subtaskOptionsModal" tabindex="-1"  aria-hidden="true">
        <form id = "updatesubTaskStatus" enctype="multipart/form-data">
            <div class="modal-dialog">
                <div class="modal-content"></div>
            </div>
        </form>
    </div>

    <!--update task modal -->
    <div class="modal fade" id="updateTaskModal" tabindex="-1"  aria-hidden="true">
        <form id = "updateTaskForm" enctype="multipart/form-data">
            <div class="modal-dialog">
                <div class="modal-content"></div>
            </div>
        </form>
    </div>
    
    <!-- view trash modal -->
    <div class="modal fade" id="viewTrashModal" tabindex="-1"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title fs-6 m-0 fw-bold">Trash</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="p-0 m-0" style="font-size:13px;">Note:  Items in the trash will be automatically deleted after 30 days from the date they were moved to trash. Please restore important items before they expire</p>
                    <hr>
                    <div class="row viewTrashRow"></div>
                </div>
            </div>
        </div>
    </div>
</body>

<div class="custom_toast"></div>
<div class="confirm_bg"></div>
<div class="confirm_main_outer">
    <div class="row w-100 m-0">
        <div class="col-lg-3 mx-auto">
            <div class="confirm_main"></div>
        </div>
    </div>
</div>

</html>

<script>
    let BASE_URL = "{{ config('app.url') }}";
    let page_limit = 10;
    let page_sort_by = null;
    let checkbox_tag_published = $("#checkbox_tag_published").prop("checked");
    let checkbox_tag_draft = $("#checkbox_tag_draft").prop("checked");
    let checkbox_status_todo = $("#checkbox_status_todo").prop("checked");
    let checkbox_status_inprogress = $("#checkbox_status_inprogress").prop("checked");
    let checkbox_status_done = $("#checkbox_status_done").prop("checked");

   

    $(document).ready(function(){
        initializeTasks(1,page_limit);
        $('#addTaskForm').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let url = "{{ route('tasks.add') }}";
            $.ajax({
                url,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if(response.success){
                        custom_toast('success',response.message);
                        $("#newTaskModal").modal('hide');
                        initializeTasks(1,page_limit);
                    }else{
                        custom_toast('error','Something went wrong');
                    }
                    
                },
                error: function(response) {
                    var errors = response.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        custom_toast('error',value[0]);
                    });
                }
            });

            setTimeout(() => {
                $(".addTaskFormInput").val("");
                $(".addTaskFormInput_checkbox").prop('checked', false);
            }, 500);
        });

        $('#addSubTaskForm').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let url = "{{ route('subtasks.add') }}";
            $.ajax({
                url,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if(response.success){
                        custom_toast('success',response.message);
                        $("#newSubTasksModal").modal('hide');
                        initializeTasks(1,page_limit);
                    }else{
                        custom_toast('error','Something went wrong');
                    }
                    
                },
                error: function(response) {
                    var errors = response.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        custom_toast('error',value[0]);
                    });
                }
            });

            setTimeout(() => {
                $(".addsubTaskFormInput").val("");
            }, 500);
        });

        

        $('#updateTaskStatus').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let url = "{{ route('tasks_status.update') }}";
            $.ajax({
                url,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if(response.success){
                        custom_toast('success',response.message);
                        $("#taskOptionsModal").modal('hide');
                        initializeTasks(1,page_limit);
                    }else{
                        custom_toast('error','Something went wrong');
                    }
                },
                error: function(response) {
                    var errors = response.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        custom_toast('error',value[0]);
                    });
                }
            });
        });

        $('#updatesubTaskStatus').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let url = "{{ route('subtasks.changestatus') }}";
            $.ajax({
                url,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if(response.success){
                        custom_toast('success',response.message);
                        $("#subtaskOptionsModal").modal('hide');
                        initializeTasks(1,page_limit);
                    }else{
                        custom_toast('error','Something went wrong');
                    }
                },
                error: function(response) {
                    var errors = response.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        custom_toast('error',value[0]);
                    });
                }
            });
        });

        $('#updateTaskForm').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let url = "{{ route('tasks.update') }}";
            $.ajax({
                url,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response);
                    if(response.success){
                        custom_toast('success',response.message);
                        $("#updateTaskModal").modal('hide');
                        initializeTasks(1,page_limit);
                    }else{
                        custom_toast('error','Something went wrong');
                    }
                },
                error: function(response) {
                    var errors = response.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        custom_toast('error',value[0]);
                    });
                }
            });
        });
    });

    function initializeTasks(page = 1, limit=50, search = null, sort_by = null){
        if(!sort_by){
            sort_by = page_sort_by;
        }

        initializeFilterValues();
        let filter_by = {
            checkbox_tag_published,
            checkbox_tag_draft,
            checkbox_status_todo,
            checkbox_status_inprogress,
            checkbox_status_done,
        };


        $("#task_list").html(`<p class="text-center">loading ...</p>`);
        $(".task_list_pagination").remove();


        $.get("{{ URL('tasks/get') }}", { limit: limit, page: page, search: search, sort_by, filter_by })
        .done(function(data) {
            let success = data.success;
            if(success && data.result.data.length){
                let tasks = data.result.data;
                let html = '';
                let paginationHtml = '';
                // Render tasks
                tasks.map(task => {
                    let image = null;
                    let images_HTML = '';
                    let images = JSON.parse(task.task_images);
                    if (images && images.length > 0) {
                        images_HTML = `<div class="mt-2">
                                        <small class="m-0  text-muted">Attachments:</small>
                                        <div class="row">`;
                                        images.map((value,i)=>{
                                            image = `${BASE_URL}/storage/${images[i]}`;
                                            images_HTML += `<div class="col-xl-1 col-lg-1 col-md-3 col-sm-3 col-3">
                                                                <div class="shadow border border-2 border-light">
                                                                    <img class="w-100" src="${image}" alt="">
                                                                </div>
                                                            </div>`;
                                        });
                                           
                                images_HTML += ` </div>
                                    </div>`;
                    }


                    let sub_tasksHTML = '';
                    let sub_tasks = JSON.parse(task.sub_tasks);
                    if(sub_tasks && sub_tasks.length){
                        sub_tasksHTML += `<hr class="m-0 mt-2">
                                        <div class="px-3">
                                        <p class="fw-bold m-2">Sub Tasks:</p>`;
                        sub_tasks.map(i=>{
                            console.log(i)
                            sub_tasksHTML += `
                                        <div class="card  p-2 mb-1">
                                            <div class="">
                                                <div class="d-flex justify-content-between">
                                                    <p class="m-0">Title: ${i['title']}</p>`;
                                            // if(i['status'] == 'done'){
                                            //     sub_tasksHTML += ``;
                                            // }else if(i['status'] == 'to-do'){
                                            //     sub_tasksHTML += `<button class="btn btn-sm text-light primary_background subTaskInProgress" data-task-id='${task['task_id']}' data-subtask-id='${i['id']}'><i class="fa fa-caret-right"></i> Start progress</button>`;
                                            // }else if(i['status'] == 'in-progress'){
                                            //     sub_tasksHTML += `<button class="btn btn-sm text-light bg-success subTaskDoneBtn" data-task-id='${task['task_id']}' data-subtask-id='${i['id']}'><i class="fa fa-check"></i> Mark as done</button>`;
                                            // }

                                            sub_tasksHTML += `<button id="subtask_option_btn" class="btn btn-sm primary_background text-light shadow" data-task-id='${task['task_id']}' data-subtask-id='${i['id']}'><i class="fa fa-gear"></i> Options</button>`;
                                        sub_tasksHTML +=`        </div>
                                                <p class="m-0">Status: ${getBadgeStatus(i['status'])}</p>
                                                <p class="m-0">Description: ${i['content']}</p>
                                            </div>
                                        </div>
                                    `;
                        })
                        sub_tasksHTML +=`</div>`;
                    }

                    html += `<div class="col-12 mb-2">
                                <div class="card bg-light rounded-sm p-4 px-5 pt-2">
                                    <h5 class="fw-bold text-decoration-underline text-capitalize">${task['title']} </h5>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                             <span>Status: ${getBadgeStatus(task['status'])}</span>
                                            ${task['tag'] == 'draft' ? `<span class="badge bg-secondary">Marked as draft</span>` : ''}
                                        </div>
                                        <div>
                                            <button id="task_update_btn" class="btn btn-sm  shadow" data-task-id='${task['task_id']}'><i class="fa fa-edit"></i> Edit</button>
                                            <button id="task_option_btn" class="btn btn-sm primary_background text-light shadow" data-task-id='${task['task_id']}'><i class="fa fa-gear"></i> Options</button>
                                            <button id="sub_tasks_btn" class="btn btn-sm shadow" data-task-id='${task['task_id']}' data-task-title='${task['title']}' data-bs-toggle="modal" data-bs-target="#newSubTasksModal"><i class="fa fa-list"></i> Add sub tasks</button>
                                            <button class="btn btn-sm shadow" data-task-id='${task['task_id']}' onclick="moveToTrash(${task['task_id']})"><i class="fa fa-trash"></i> Move to trash </button>
                                        </div> 
                                    </div>
                                    <small class="text-muted ">Date: ${task['date_added']}</small>
                                    <div class="mt-1">
                                        <small class="m-0  text-muted">Content/Description:</small>
                                        <p class="m-0 ">${task['content']}</p>
                                    </div>
                                    ${images_HTML}
                                    ${sub_tasksHTML}

                                </div>
                                
                            </div>`;
                            });

                $("#task_list").html(html);
                $(".task_list_pagination").remove();
                paginationHtml += `<nav class="task_list_pagination" aria-label="Page navigation"><ul class="pagination">`;
                if (data.result.page > 1) {
                    paginationHtml += `<li class="page-item"><button class="page-link" onclick="initializeTasks(${data.result.page - 1}, ${limit}, ${search ? toString(search) : null})">Previous</button></li>`;
                }
                for (let i = 1; i <= data.result.pages; i++) {
                    paginationHtml += `<li class="page-item ${data.result.page == i ? 'active' : ''}"><button class="page-link" onclick="initializeTasks(${i}, ${limit}, ${search ? toString(search) : null})">${i}</button></li>`;
                }
                if (data.result.page < data.result.pages) {
                    paginationHtml += `<li class="page-item"><button class="page-link" onclick="initializeTasks(${data.result.page + 1}, ${limit}, ${search ? toString(search) : null})">Next</button></li>`;
                }
                paginationHtml += `</ul></nav>`;
                $("#task_list").after(paginationHtml);
            }else{
                $("#task_list").html('no data');
                $(".task_list_pagination").remove();
            }
        })
        .fail(function() {
            alert("Error loading tasks");
        });
    }


    $(document).on('change','#page_limit', function(){
        let $this = $(this);
        let value = $this.val();
        page_limit = value;
        initializeTasks(1,page_limit);
    })

    $(document).on('change','#page_sort_by', function(){
        let $this = $(this);
        let value = $this.val();
        page_sort_by = value;
        initializeTasks(1,page_limit, null, value);
    })

    $(document).on('change','.filter_checkbox', function(){
        initializeTasks(1,page_limit, null);
    })

    $(document).on('click', "#sub_tasks_btn", function(){
        let task_id = $(this).attr('data-task-id');
        let task_title = $(this).attr('data-task-title');
        $("#addSubTaskForm .task_id_input").val(task_id)
        $("#addSubTaskForm .main_task_title").text(task_title);
    });

    function initializeFilterValues(){
        checkbox_tag_published = $("#checkbox_tag_published").prop("checked");
        checkbox_tag_draft = $("#checkbox_tag_draft").prop("checked");
        checkbox_status_todo = $("#checkbox_status_todo").prop("checked");
        checkbox_status_inprogress = $("#checkbox_status_inprogress").prop("checked");
        checkbox_status_done = $("#checkbox_status_done").prop("checked");
    }


    let debounceTimeout;
    $(document).on('keyup change click', '#search_task', function() {
        let $this = $(this);
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(function() {
            let value = $this.val();
            initializeTasks(1,page_limit, value);
        }, 500); 
    });

    $(document).on('click','#task_option_btn', function(){
        let $this = $(this);
        let task_id = $this.attr('data-task-id');

        $.get(`{{ URL('tasks/get/${task_id}') }}`, {  })
        .done(function(data) {
            let success = data.success;
            if(success){
                let task = data.result;
                let html = `
                        <input type="hidden" value="${task['task_id']}" name="task_id">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5 text-capitalize">${task['title']}</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-2">
                                <label>Mark Status as:</label>
                                <select class="form-control form-control-sm" name="status" value="${task['status']}">
                                    <option value='to-do' ${task['status'] === 'to-do' ? 'selected' : ''}>To do</option>
                                    <option value='in-progress' ${task['status'] === 'in-progress' ? 'selected' : ''}>In Progress</option>
                                    <option value='done' ${task['status'] === 'done' ? 'selected' : ''}>Done</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <div class="mb-2">
                                    <input type="checkbox" id="save_as_draft_checkbox" name="save_as_draft_checkbox" value="1" ${task['tag'] == 'draft' ? 'checked' : ''}>
                                    <label for="save_as_draft_checkbox"> Save as draft</label><br>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-sm primary_background text-light">Save</button>
                        </div>
            `;

            $("#taskOptionsModal .modal-content").html(html);
            $("#taskOptionsModal").modal('show');

            }
        })
        .fail(function() {
            alert("Error loading tasks");
        });
    })

    $(document).on('click','#subtask_option_btn', function(){
        let $this = $(this);
        let task_id = $this.attr('data-task-id');
        let subtask_id = $this.attr('data-subtask-id');
        

        $.get(`{{ URL('tasks/get/${task_id}') }}`, {  })
        .done(function(data) {
            let success = data.success;
            if(success && data.result.sub_tasks){
                let sub_tasks = JSON.parse(data.result.sub_tasks);
                sub_tasks = sub_tasks.find(task => task.id === subtask_id);

                // let task = data.result;
                let html = `
                        <input type="hidden" value="${data.result['task_id']}" name="task_id">
                        <input type="hidden" value="${sub_tasks['id']}" name="subtask_id">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5 text-capitalize">${sub_tasks['title']}</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-2">
                                <label>Mark Status as:</label>
                                <select class="form-control form-control-sm" name="status" value="${sub_tasks['status']}">
                                    <option value='to-do' ${sub_tasks['status'] === 'to-do' ? 'selected' : ''}>To do</option>
                                    <option value='in-progress' ${sub_tasks['status'] === 'in-progress' ? 'selected' : ''}>In Progress</option>
                                    <option value='done' ${sub_tasks['status'] === 'done' ? 'selected' : ''}>Done</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-sm primary_background text-light">Save</button>
                        </div>
            `;

            $("#subtaskOptionsModal .modal-content").html(html);
            $("#subtaskOptionsModal").modal('show');

            }
        })
        .fail(function() {
            alert("Error loading tasks");
        });
    })

    $(document).on('click','#task_update_btn', function(){
        let $this = $(this);
        let task_id = $this.attr('data-task-id');

        $.get(`{{ URL('tasks/get/${task_id}') }}`, {  })
        .done(function(data) {
            let success = data.success;
            if(success){
                let task = data.result;
                let image_html = ``;
                if(task){
                    // for current images
                    let current_task_images = JSON.parse(task.task_images);
                    if((current_task_images).length){
                        $(current_task_images).each(function(i){
                            let image = `${BASE_URL}/storage/${current_task_images[i]}`;
                            image_html += `<div style="height:50px;width:50px;" class="position-relative me-2 task_image_div_${i}">
                                                <img class="w-100 shadow-sm" src="${image}" alt="">
                                                <div class="delete_image_button d-flex justify-content-center align-items-center" onclick="deleteTaskImageOnUpdate('${current_task_images[i]}',${i})">
                                                    <span class="fs-6 text-light text-center"><i class="fas fa-trash-alt"></i></span>
                                                </div>
                                            </div>`;
                        })
                        
                        $(".current_task_images_div").html(image_html);
                    }
                }
                
                let html = `
                    <input type="hidden" value="${task['task_id']}" name="task_id">
                    <input type="hidden" name="image_to_delete" >
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Edit task</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label>Title</label>
                            <input type="text" name="title" value="${task['title']}" class="form-control form-control-sm" required placeholder="Title">
                        </div>
                        <div class="mb-2">
                            <label>Content</label>
                            <textarea name="content" id=""  class="form-control form-control-sm" required>${task['content']}</textarea>
                        </div>
                        <div class="mb-2">
                            <input type="file" name="images[]" class="form-control form-control-sm shadow-none outline-none" multiple>
                        </div>
                        <div class="d-flex">
                            ${image_html}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm primary_background text-light">Update</button>
                    </div>
            `;

            $("#updateTaskModal .modal-content").html(html);
            $("#updateTaskModal").modal('show');

            }
        })
        .fail(function() {
            alert("Error loading tasks");
        });
    })

    $(document).on('click', '.subTaskDoneBtn', function(){

        let task_id = $(this).attr('data-task-id');
        let subtask_id = $(this).attr('data-subtask-id');
        let status = 'done';

        let data = {
            task_id,subtask_id,status
        }

        $.post("{{ route('subtasks.changestatus') }}",data, function(response){
            if(response.success){
                custom_toast('success', response.message);
                initializeTasks(1,page_limit);
            }
        })

    })

    $(document).on('click', '.subTaskInProgress', function(){
        let task_id = $(this).attr('data-task-id');
        let subtask_id = $(this).attr('data-subtask-id');
        let status = 'in-progress';

        let data = {
            task_id,subtask_id,status
        }

        $.post("{{ route('subtasks.changestatus') }}",data, function(response){
            if(response.success){
                custom_toast('success', response.message);
                initializeTasks(1,page_limit);
            }
        })

    })

    

    function deleteTaskImageOnUpdate(imageName, i) {
        let imageToDelete = $("#updateTaskForm [name='image_to_delete']").val();
        
        let array = [];

        if (imageToDelete) {
            array = JSON.parse(imageToDelete); 
        }

        if (!array.includes(imageName)) { 
            array.push(imageName);
        }
        
        $(`.task_image_div_${i}`).remove();

        $("#updateTaskForm [name='image_to_delete']").val(JSON.stringify(array));
    }

    function moveToTrash(id, toTrash=true){
        if(id){
            let data = {task_id: id, toTrash};
            $.post(`{{ route('task.trash') }}`, data, function(response) {
                if(response.success){
                    custom_toast('success', response.message);
                    initializeTasks(1,page_limit);
                }else{
                    custom_toast('error', response.message);
                }
            });

            initializeTrashList();
        }

    }

    $(document).on('click', '.viewTrashModalBtn',function(){
        initializeTrashList();
    })

    function initializeTrashList(){
        let html = ``;
        $(".viewTrashRow").html(`<p>No items ... </p>`);

        $.get("{{ route('trash.get') }}",function(response){
            if(response.success && response.result.data.length){
                let trash = response.result.data;
                trash.map(i=>{
                    console.log(i)
                    html+= `<div class="col-12">
                            <div class="card shadow p-2 d-flex mb-1">
                                <div class="d-flex  justify-content-between">
                                     <div class="position-relative" style="max-width: 85%;">
                                        <p class="m-0 p-0 text-decoration-undeline">${i['title']}</p>
                                        <p class="text-muted text-truncate" style="font-size:13px;">${i['content']}</p>
                                    </div>
                                    <button class="btn btn-sm" onclick="moveToTrash(${i['task_id']}, false)"><i class="fa fa-repeat" ></i> Restore</button>
                                </div>
                            </div>
                        </div>`
                })
                $(".viewTrashRow").html(html);
            }
        })
    }
    
</script>