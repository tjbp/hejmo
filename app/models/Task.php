<?php

namespace Models;

class Task extends \Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tasks';

    /**
     * The database table's primary key.
     *
     * @var string
     */
    protected $primaryKey = 'task_id';

    /**
     * Disable automatic timestamp column population.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Array of properties protected from mass-assignment.
     *
     * @var array
     */
    protected $guarded = [];

    const WINTER = 1;
    const SPRING = 2;
    const SUMMER = 4;
    const AUTUMN = 8;

    const DAY = 86400;
    const WEEK = 604800;
    const MONTH = 2629739;
    const YEAR = 31557600;

    /**
     * Accessor to convert properties to snake_case, as in the database.
     *
     * @param string $property
     * @return mixed
     */
    public function __get($property)
    {
        return parent::__get(snake_case($property));
    }

    /**
     * Mutator to convert properties to snake_case, as in the database.
     *
     * @param string $property
     * @param mixed $value
     * @return void
     */
    public function __set($property, $value)
    {
        parent::__set(snake_case($property), $value);
    }

    /**
     * Relationships to tasks that block this task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function blocker()
    {
        return $this->belongsTo('\Models\Task', 'blocker_id');
    }

    /**
     * Relationships to tasks that this task blocks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function blockees()
    {
        return $this->hasMany('\Models\Task', 'blocker_id');
    }

    /**
     * Fetcher for tasks that don't have an incomplete blocker.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public static function viable()
    {
        return self::whereRaw('((select count(*) from `hejmo_tasks` as `hejmo_count` where `hejmo_tasks`.`blocker_id` = `hejmo_count`.`task_id`) = 0 or (select count(*) from `hejmo_tasks` as `hejmo_count` where `hejmo_tasks`.`blocker_id` = `hejmo_count`.`task_id` and `complete` = 1) >= 1)');
    }

    /**
     * Check whether a task is due soon.
     *
     * @return boolean
     */
    public function isSoon()
    {
        return ($this->due - time()) < 345600;
    }

    /**
     * Check whether a task is overdue.
     *
     * @return boolean
     */
    public function isOverdue()
    {
        return ($this->due - time()) < 0;
    }

    /**
     * Check whether a task is complete.
     *
     * @return boolean
     */
    public function isComplete()
    {
        return $this->complete == 1.00;
    }

    /**
     * Bump an overdue task to a new due date, but clone it first so that the
     * missed iteration isn't forgotten.
     *
     * @return void
     */
    public function bumpRecurring()
    {
        $old_task = new self;

        $old_task->fill($this->attributes);

        $old_task->task_id = null;

        $old_task->recurring = false;

        $old_task->gap = 0;

        $old_task->season = 0;

        $old_task->save();

        $this->due += $this->gap;

        $this->complete = 0.00;
    }

    /**
     * Return an array of the seasons that this task is valid for.
     *
     * @return array
     */
    public function seasonNames()
    {
        $seasons = [];

        if (self::WINTER & $this->season) {
            $seasons[] = 'winter';
        }

        if (self::SPRING & $this->season) {
            $seasons[] = 'spring';
        }

        if (self::SUMMER & $this->season) {
            $seasons[] = 'summer';
        }

        if (self::AUTUMN & $this->season) {
            $seasons[] = 'autumn';
        }

        return $seasons;
    }

    /**
     * Check whether a task is currently in season.
     *
     * @return boolean
     */
    public function inSeason()
    {
        // No defined season means always in season
        if (!$this->season) return true;

        $dates = [
            '/11/21' => self::WINTER,
            '/08/21' => self::AUTUMN,
            '/05/21' => self::SUMMER,
            '/02/21' => self::SPRING,
            '/12/31' => self::WINTER,
        ];

        foreach ($dates AS $key => $value) {
            $date = date("Y") . $key;

            if (time() > strtotime($date)) {
                return ($this->season & $value);
            }
        }
    }

    public function height()
    {
        $branches = [0];

        if (!$this->blockees->isEmpty()) {
            foreach ($this->blockees as $task) {
                $branches[] = $task->height();
            }

            return max($branches) + 1;
        }

        return 1;
    }

    public function rootBlocker()
    {
        if (!$blocker = $this->blocker) {
            return false;
        }

        while ($blocker->blocker) {
            $blocker = $blocker->blocker;
        }

        return $blocker;
    }

    public static function treeHeight()
    {
        $heights = [0];

        foreach (self::where('blocker_id', 0)->get() as $task) {
            $heights[] = $task->height();
        }

        return max($heights);
    }
}
