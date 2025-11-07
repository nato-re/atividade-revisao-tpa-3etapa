@foreach($tasks as $task)
    <div class="task-item">
        <h3>{{ $task->title }}</h3>
        <p>{{ $task->description }}</p>
    </div>
@endforeach
