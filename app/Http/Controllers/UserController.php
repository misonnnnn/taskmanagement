<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    const TASK_STATUS_TO_DO = 'to-do';

    const TASK_STATUS_IN_PROGRESS = 'in-progress';

    const TASK_STATUS_DONE = 'done';

    const TASK_STATUS_TRASH = 'trash';

    const TASK_TAG_PUBLISHED = 'published';

    const TASK_TAG_DRAFT = 'draft';

    public function showHome()
    {
        return view('User.home');
    }

    public function addTask(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'content' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        if (! Auth::check()) {
            return $this->returnUnauthorizedResponse();
        }

        $imagePaths = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $fileName = time().'_'.$file->getClientOriginalName();
                $filePath = $file->storeAs('images', $fileName, 'public');
                $imagePaths[] = $filePath;
            }
        }

        $task = new Task;
        $task->title = $request->title;
        $task->user_id = Auth::user()->id;
        $task->content = $request->content;
        $task->status = self::TASK_STATUS_TO_DO;

        if ($request->save_as_draft_checkbox == 1) {
            $task->tag = self::TASK_TAG_DRAFT;
        } else {
            $task->tag = self::TASK_TAG_PUBLISHED;
        }

        $task->images = json_encode($imagePaths);

        try {
            $task->save();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while saving the task.',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json(['success' => true, 'message' => 'Task created successfully!']);
    }

    public function returnUnauthorizedResponse(){
        $result = [
            'success' => false,
            'message' => 'Unauthorized access',
            'result' => [],
        ];

        return response()->json($result, 401);
    }

    public function returnTaskNotFound(){
        return response()->json([
            'success' => false,
            'message' => 'Task not found.',
            'result' => [],
        ], 404);
    }

    public function getTasks(Request $request): JsonResponse
    {
        $limit = $request->input('limit', 10); // Default limit is 10
        $page = $request->input('page', 1); // Default page is 1
        $search = $request->input('search'); // Search keyword
        $sort_by = $request->input('sort_by'); // Sort By
        $filter_by = $request->input('filter_by', []); // Filter by

        if (! Auth::check()) {
            return $this->returnUnauthorizedResponse();
        }

        $query = Task::query();

        $query->join('users', 'tasks.user_id', '=', 'users.id')
            ->select('tasks.sub_tasks', 'users.username as username', 'users.id as user_id', 'tasks.tag as tag', 'tasks.status', 'tasks.id as task_id', 'tasks.title', 'tasks.content', 'tasks.images as task_images', 'tasks.updated_at as date_added');
        $query->where('tasks.user_id', Auth::user()->id);

        if ($request->getTrash) {
            $query->where('tasks.status', self::TASK_STATUS_TRASH);
        } else {
            $query->where('tasks.status', '!=', self::TASK_STATUS_TRASH);
        }

        $tags = [];
        $statuses = [];

        if (! empty($filter_by['checkbox_tag_published']) && $filter_by['checkbox_tag_published'] == 'true') {
            $tags[] = self::TASK_TAG_PUBLISHED;
        }
        if (! empty($filter_by['checkbox_tag_draft']) && $filter_by['checkbox_tag_draft'] == 'true') {
            $tags[] = self::TASK_TAG_DRAFT;
        }
        if (! empty($filter_by['checkbox_status_todo']) && $filter_by['checkbox_status_todo'] == 'true') {
            $statuses[] = self::TASK_STATUS_TO_DO;
        }
        if (! empty($filter_by['checkbox_status_inprogress']) && $filter_by['checkbox_status_inprogress'] == 'true') {
            $statuses[] = self::TASK_STATUS_IN_PROGRESS;
        }
        if (! empty($filter_by['checkbox_status_done']) && $filter_by['checkbox_status_done'] == 'true') {
            $statuses[] = self::TASK_STATUS_DONE;
        }

        if (! empty($tags)) {
            $query->whereIn('tasks.tag', $tags);
        }
        if (! empty($statuses)) {
            $query->whereIn('tasks.status', $statuses);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('tasks.title', 'like', "%{$search}%")
                    ->orWhere('tasks.content', 'like', "%{$search}%")
                    ->orWhere('tasks.created_at', 'like', "%{$search}%");
            });
        }

        // Sorting
        switch ($sort_by) {
            case 'title_asc':
                $query->orderBy('tasks.title', 'ASC');
                break;
            case 'title_desc':
                $query->orderBy('tasks.title', 'DESC');
                break;
            case 'date_asc':
                $query->orderBy('tasks.updated_at', 'ASC');
                break;
            case 'date_desc':
            default:
                $query->orderBy('tasks.updated_at', 'DESC');
                break;
        }

        // Paginate the results
        $tasks = $query->paginate($limit, ['*'], 'page', $page);

        $result = [
            'success' => true,
            'result' => [
                'page' => $tasks->currentPage(),
                'pages' => $tasks->lastPage(),
                'count' => $tasks->total(),
                'limit' => intval($limit),
                'data' => $tasks->items(),
            ],
        ];

        return response()->json($result);
    }

    public function getTask(Request $request)
    {
        if (! Auth::check()) {
            return $this->returnUnauthorizedResponse();
        }

        $id = $request->id;
        $task = Task::join('users', 'tasks.user_id', '=', 'users.id')
            ->select('tasks.sub_tasks', 'users.username as username', 'users.id as user_id', 'tasks.tag as tag', 'tasks.status', 'tasks.id as task_id', 'tasks.title', 'tasks.content', 'tasks.images as task_images', 'tasks.updated_at as date_added')
            ->where('tasks.id', $id)
            ->first();

        if ($task->user_id != Auth::user()->id) {
            return $this->returnUnauthorizedResponse();
        }

        // success
        if (! empty($task)) {
            $result = [
                'success' => true,
                'message' => 'success',
                'result' => $task,
            ];
        } else {
            $result = [
                'success' => false,
                'message' => 'Something went wrong.',
                'result' => [],
            ];
        }

        return response()->json($result);
    }

    public function updateTaskStatus(Request $request): JsonResponse
    {
        $request->validate([
            'task_id' => 'required',
            'status' => 'required|string',
        ]);

        $task = Task::find($request->task_id);

        if (! $task) {
            return $this->returnTaskNotFound();
        }

        if (! Auth::check()) {
            return $this->returnUnauthorizedResponse();
        }

        if ($task->user_id != Auth::user()->id) {
            return $this->returnUnauthorizedResponse();
        }

        $task->status = $request->status;
        if ($request->save_as_draft_checkbox == 1) {
            $task->tag = self::TASK_TAG_DRAFT;
        } else {
            $task->tag = self::TASK_TAG_PUBLISHED;
        }

        try {
            $task->save();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while saving the task.',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Updating task status success',
        ]);
    }

    public function updateTask(Request $request): JsonResponse
    {
        $request->validate([
            'task_id' => 'required',
            'title' => 'required|string|max:100',
            'content' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'image_to_delete' => 'nullable|string',
        ]);

        $task = Task::find($request->task_id);

        if (! $task) {
            return $this->returnTaskNotFound();
        }

        if (! Auth::check()) {
            return $this->returnUnauthorizedResponse();
        }

        if ($task->user_id != Auth::user()->id) {
            return $this->returnUnauthorizedResponse();
        }

        $image_to_delete = json_decode($request->image_to_delete, true);

        $imagePaths = json_decode($task->images, true) ?: [];

        if (! empty($image_to_delete)) {
            $imagePaths = array_diff($imagePaths, $image_to_delete); // Remove images in $image_to_delete
            $imagePaths = array_values($imagePaths);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $fileName = uniqid().'_'.$file->getClientOriginalName();
                $filePath = $file->storeAs('images', $fileName, 'public');
                $imagePaths[] = $filePath;
            }
        }

        $task->title = $request->title;
        $task->user_id = Auth::user()->id;
        $task->content = $request->content;
        $task->images = json_encode($imagePaths);

        try {
            $task->save();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while saving the task.',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Updating task status success',
        ]);
    }

    public function addSubTasks(Request $request): JsonResponse
    {
        $request->validate([
            'task_id' => 'required|integer',
            'title' => 'required|string|max:100',
            'content' => 'required|string',
        ]);

        $task = Task::find($request->task_id);

        if (! $task) {
            return $this->returnTaskNotFound();
        }

        if (! Auth::check()) {
            return $this->returnUnauthorizedResponse();
        }

        if ($task->user_id !== Auth::user()->id) {
            return $this->returnUnauthorizedResponse();
        }

        $sub_tasks = json_decode($task->sub_tasks, true) ?? [];

        $new_sub_task = [
            'id' => uniqid(),
            'title' => $request->title,
            'content' => $request->content,
            'status' => self::TASK_STATUS_TO_DO,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
            'completed' => false,
        ];

        $sub_tasks[] = $new_sub_task;

        $task->sub_tasks = json_encode($sub_tasks);

        try {
            $task->save();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while saving the task.',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sub Task created successfully!',
            'result' => $new_sub_task,
        ]);
    }

    public function subTasksChangeStatus(Request $request): JsonResponse
    {
        $request->validate([
            'task_id' => 'required',
            'status' => 'required',
            'subtask_id' => 'required',
        ]);

        $task = Task::find($request->task_id);

        if (! $task) {
            return $this->returnTaskNotFound();
        }

        if (! Auth::check()) {
            return $this->returnUnauthorizedResponse();
        }

        if ($task->user_id !== Auth::user()->id) {
            return $this->returnUnauthorizedResponse();
        }

        $sub_tasks = json_decode($task->sub_tasks, true) ?? [];

        $updated = false;

        foreach ($sub_tasks as &$sub_task) {
            if (isset($sub_task['id']) && $sub_task['id'] == $request->subtask_id) {
                $sub_task['status'] = $request->status;
                $updated = true;
                break;
            }
        }

        if (! $updated) {
            return response()->json([
                'success' => false,
                'message' => 'Sub-task not found.',
                'result' => [],
            ], 404);
        }

        $task->sub_tasks = json_encode($sub_tasks);

        // checks if all the sub ticket are done
        $allDone = ! collect($sub_tasks)->contains(function ($subtask) {
            return $subtask['status'] !== self::TASK_STATUS_DONE;
        });

        if ($allDone) {
            $task->status = self::TASK_STATUS_DONE;
        }

        try {
            $task->save();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while saving the task.',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sub-task marked as '.$request->status.' successfully.',
            'result' => $sub_tasks,
        ]);
    }

    public function taskMoveToTrash(Request $request): JsonResponse
    {
        $request->validate([
            'task_id' => 'required',
            'toTrash' => 'required',
        ]);

        $task = Task::find($request->task_id);

        if (! $task) {
            return $this->returnTaskNotFound();
        }

        if (! Auth::check()) {
            return $this->returnUnauthorizedResponse();
        }

        if ($task->user_id !== Auth::user()->id) {
            return $this->returnUnauthorizedResponse();
        }
        if ($request->boolean('toTrash')) {
            $task->status = self::TASK_STATUS_TRASH;
        } else {
            // restore
            $task->status = self::TASK_STATUS_TO_DO;
        }

        try {
            $task->save();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while saving the task.',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Moving task to trash success',
        ]);
    }

    public function getTrash(): JsonResponse
    {
        $requestData = [
            'getTrash' => true,
        ];

        $newRequest = new Request($requestData);

        return $this->getTasks($newRequest);
    }
}
