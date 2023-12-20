<?php
?>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-4 text-center">
                <h2>Todo App</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-4 text-center">
                <form action="{{ route('tasks.store') }}" method="POST">
                    <div class="form-group">
                        <input type="text" name="name" id="name" placeholder="Enter Task Name" />
                        <button type="button" class="add-button btn btn-sm btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row text-center">
            <div class="col-4">
                <ul class="todo-list sortable" data-priority-update-route="{{ route('tasks.updateTaskPriorities') }}">
                    @foreach($tasks as $task)
                        <li class="todo-item-container" style="list-style: none;" data-task-id="{{ $task->id }}">
                            <div class="todo-item">
                                <div class="card">
                                    <div class="card-body">
                                        <form class="edit-todo-item-checkbox" action="{{ route('tasks.toggledCompleted', [$task->id]) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="d-flex justify-content-between">
                                                <span class="display-todo-item">
                                                    <input type="checkbox" name="completed" class="form-check-input me-1" @if($task->completed) checked @endif />
                                                    <label class="form-check-label todo-item-label"> {{ $task->name }}</label>
                                                </span>

                                                <div class="d-flex flex-row">
                                                    <button type="button" class="edit-button btn btn-sm btn-info display-todo-item me-1">Edit</button>
                                                    <button type="button" class="delete-button btn btn-sm btn-danger display-todo-item" data-delete-route="{{ route('tasks.destroy', [$task->id]) }}">Delete</button>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="col">
                                            <form class="edit-todo-item-label" style="display: none;" action="{{ route('tasks.update', [$task->id]) }}" method="POST">
                                                @method('PATCH')

                                                <div class="form-group">
                                                    <input type="text" name="name" id="name" placeholder="Enter Task Name" />
                                                    <button type="button" class="save-edit-button btn btn-sm btn-primary">Save</button>
                                                    <button type="button" class="cancel-edit-button btn btn-sm btn-warning">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $(function() {
                $(".sortable").sortable({
                    update: function (event, ui) {
                        const $target = $(event.target);
                        const $task_list = $('.todo-list').first();

                        let data = {
                            'items': []
                        };
                        let counter = 1;
                        $task_list.find('li').each((index, value)=>{
                            data.items.push({
                                'task_id': $(value).data('task-id'),
                                'priority':counter++
                            });
                        });

                        $.ajax({
                            type: 'POST',
                            url: $task_list.data('priority-update-route'),
                            contentType: 'application/json',
                            data: JSON.stringify(data),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                // do nothing
                            },
                            error: function(response) {
                                console.log('error:');
                                console.log(response);

                                toastr.options.timeOut = 3000;
                                toastr.error('There was an error re-sorting your tasks. Please try again.', 'Error');
                            }
                        });
                    }
                });
            });

            $('.todo-list').find('.form-check-input').each((index, target)=>{
                const $target = $(target);
                const $task_label = $target.parent().find('.todo-item-label').first();

                if ($target.is(':checked')) {
                    $task_label.css({
                        textDecoration: 'line-through',
                        color: 'red'
                    });
                }
            });

            $('.form-check-input').on('click', (e)=>{
                const $this = $(e.target);
                const val = $this.is(':checked');
                const $task_label = $this.parent().find('.todo-item-label').first();
                const $form = $this.parents('form').first();

                if (val) {
                    $task_label.css({
                        textDecoration: 'line-through',
                        color: 'red'
                    });
                }
                else {
                    $task_label.css({
                        textDecoration: 'none',
                        color: 'black'
                    });
                }

                $.ajax({
                    type: 'PUT',
                    url: $form.attr('action'),
                    data: $form.serialize(),
                    success: function(response) {
                        // do nothing
                    },
                    error: function(response) {
                        console.log('error:');
                        console.log(response);

                        toastr.options.timeOut = 3000;
                        toastr.error('There was an error marking your task as completed. Please try again.', 'Error');
                    }
                });
            });

            $('.add-button').on('click', (e)=>{
                e.preventDefault();
                const $this = $(e.target);
                const $form = $this.parents('form').first();

                $.ajax({
                    type: 'POST',
                    url: $form.attr('action'),
                    data: $form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        window.location.href = response.redirect;
                    },
                    error: function(response) {
                        clearFormErrors($form)
                        insertFormErrors(response.responseJSON.errors, $form);
                    }
                });
            });

            $('.edit-button').on('click', (e)=>{
                const $this = $(e.target);
                const $parent_container = $this.parents('.todo-item-container').first();

                $parent_container.find('.edit-todo-item-label').show();
                $parent_container.find('.display-todo-item').hide();
                $parent_container.find('form input[name="name"]').val($parent_container.find('.todo-item-label').html());
            });

            $('.cancel-edit-button').on('click', (e)=>{
                const $this = $(e.target);
                const $form = $this.parents('form').first()
                const $parent_container = $this.parents('.todo-item-container').first();

                $parent_container.find('.edit-todo-item-label').hide();
                $parent_container.find('.display-todo-item').show();

                clearFormErrors($form);
            });

            $('.save-edit-button').on('click', (e)=>{
                e.preventDefault();
                const $this = $(e.target);
                const $form = $this.parents('form').first();
                const $parent_container = $this.parents('.todo-item-container').first();

                $.ajax({
                    type: 'POST',
                    url: $form.attr('action'),
                    data: $form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        clearFormErrors($form);

                        $parent_container.find('.cancel-edit-button').trigger('click');
                        $parent_container.find('.todo-item-label').html(response.task.name);
                    },
                    error: function(response) {
                        clearFormErrors($form);
                        insertFormErrors(response.responseJSON.errors, $form);
                    }
                });
            });

            $('.delete-button').on('click', (e)=>{
                const $this = $(e.target);

                $.ajax({
                    type: 'POST',
                    url: $this.data('delete-route'),
                    data: '_method=DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        window.location.href = response.redirect;
                    },
                    error: function(response) {
                        console.log('Error');
                        console.log(response);

                        toastr.options.timeOut = 3000;
                        toastr.error('There was an error deleting the task. Please try again.', 'Error');
                    }
                });
            });
        });

        function insertFormErrors(jsonErrors, $form) {
            // assumes all form fields are wrapped in a div with a class of form-group
            $.each(jsonErrors, function (inputName){
                const $formField = $form.find('#'+inputName);
                $formField.closest('.form-group').addClass('has-error has-feedback');
                $formField.parent().append('<div class="error-message" style="color: red;">'+jsonErrors[inputName]+'</div>');
            });
        }

        function clearFormErrors($form) {
            $.each($form.find('.form-group'), function (){
                $(this).removeClass('has-error has-feedback');
            });

            $.each($form.find('.error-message'), function (){
                $(this).remove();
            });
        }
    </script>
</body>
