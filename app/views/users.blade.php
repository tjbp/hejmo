<div class="users">
    <h1>Users</h1>
    <table class="pure-table pure-table-horizontal">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    {{ Form::open(['url' => "/users/delete", 'class' => "pure-form"]) }}
                        {{ Form::hidden('user_id', $user->userId) }}
                        <button type="submit" class="pure-button button-error delete-button"><i class="fa fa-times"></i> Delete</button>
                    {{ Form::close() }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ Form::open(['url' => "/users/new", 'class' => "pure-form"]) }}
        <fieldset>
            {{ Form::text('name', '', ['placeholder' => "Name"]) }}
            {{ Form::email('email', '', ['placeholder' => "Email"]) }}
            <button type="submit" class="pure-button pure-button-primary"><i class="fa fa-check"></i> Save</button>
        </fieldset>
    {{ Form::close() }}
</div>
