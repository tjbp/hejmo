{{ Form::open(['url' => "/tasks/new", 'class' => "pure-form new-task"]) }}
    <h1>New Task</h1>
    <fieldset>
        <div class="pure-g" style="margin-bottom: 8px;">
            {{ Form::textarea('description', '', ['class' => "pure-u-1-1", 'placeholder' => "Description"]) }}
        </div>
        <div class="pure-g">
            <span class="pure-u-1-1 pure-u-md-3-4">
                {{ Form::text('due', '', ['class' => "datepicker", 'placeholder' => "Due"]) }}
                <label>{{ Form::checkbox('recurring') }} Recurring</label>
                <span class="recurring">
                    every {{ Form::text('gap', '', ['class' => "pure-input-1-4", 'placeholder' => "#"]) }}
                    {{ Form::select('gap_unit', [Models\Task::DAY => "days", Models\Task::WEEK => "weeks", Models\Task::MONTH => "months", Models\Task::YEAR => "years"]) }}
                    <label>{{ Form::checkbox('limit_season') }} Limit to</label>
                    <span class="season">
                        <label>{{ Form::checkbox('winter') }} winter</label>
                        <label>{{ Form::checkbox('spring') }} spring</label>
                        <label>{{ Form::checkbox('summer') }} summer</label>
                        <label>{{ Form::checkbox('autumn') }} autumn</label>
                    </span>
                </span>
            </span>
            <button type="submit" class="pure-u-1-1 pure-u-md-1-4 pure-button pure-button-primary"><i class="fa fa-check"></i> Save</button>
        </div>
    </fieldset>
{{ Form::close() }}
