<div class="pure-g">
    <div class="pure-u-1-1">
        <div class="pure-g">
            <div class="pure-u-1-1 pure-u-md-1-3">
                <div class="task {{ $task->isComplete() ? 'complete' : ($task->isOverdue() ? 'overdue' : ($task->isSoon() ? 'soon' : '')) }}" data-taskid="{{ $task->taskId }}" draggable="true">
                    <div class="pure-g">
                        <div class="pure-u-1-1 description">
                            <div class="view">{{ nl2br(htmlentities($task->description)) }}</div>
                            <div class="edit">
                                {{ Form::open(['url' => "/tasks/edit", 'class' => "pure-form"]) }}
                                    {{ Form::hidden('task_id', $task->taskId) }}
                                    {{ Form::textarea('description', $task->description) }}
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                    <div class="pure-g">
                        <div class="pure-u-1-1 info">
                            <button class="options pure-button pure-button-primary button-xsmall"><i class="fa fa-cog"></i> Options</button>
                            <div class="move">
                                <button class="pure-button button-warning button-xsmall"><i class="fa fa-move"></i> Move</button>
                            </div>
                            @if ($task->complete !== 1.00)
                                <div class="complete">
                                    {{ Form::open(['url' => "/tasks/complete", 'class' => "pure-form"]) }}
                                        {{ Form::hidden('task_id', $task->taskId) }}
                                        Complete: {{ Form::select('complete', ["0" => "0%", "0.25" => "25%", "0.5" => "50%", "0.75" => "75%", "1" => "100%"], $task->complete, ['class' => "input-xsmall"]) }}
                                        <button type="submit" class="pure-button button-success button-xsmall"><i class="fa fa-check"></i> Save</button>
                                    {{ Form::close() }}
                                </div>
                            @endif
                            <div class="delete">
                                {{ Form::open(['url' => "/tasks/delete", 'class' => "pure-form"]) }}
                                    {{ Form::hidden('task_id', $task->taskId) }}
                                    <button type="submit" class="pure-button button-error button-xsmall delete-button"><i class="fa fa-times"></i> Delete</button>
                                {{ Form::close() }}
                            </div>
                            <div class="added">Added: {{ relative_date($task->added) }}</div>
                            @if ($task->recurring)
                                <div class="gap">Recurs: every {{ relative_timespan($task->gap) }}</div>
                                <div class="due" title="{{ date('r', $task->due) }}">Next due: {{ relative_date($task->due) }}</div>
                            @else
                                <div class="due" title="{{ date('r', $task->due) }}">Due: {{ relative_date($task->due) }}</div>
                            @endif
                            @if ($task->season)
                                <div class="season">Seasons: {{ punctuate_array($task->seasonNames()) }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @if (empty($no_blockees))
                <div class="pure-u-1-1 pure-u-md-2-3">
                    @foreach ($task->blockees()->orderBy(\DB::raw("CAST(`due` AS signed) - UNIX_TIMESTAMP()"))->get() as $blockee)
                        @include('task', ['task' => $blockee])
                    @endforeach
                    <div class="pure-g">
                        <div class="pure-u-1-8 emptytask" data-taskid="{{ $task->taskId }}" droppable="true">&nbsp;</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
