<?php

namespace App\Models;

/**
 * Provides a template and an interface for user data models.
 *
 * @copyright  2023 ModernFit-Group:4
 * @category   Models
 * @since      Class available since Release 1.0.2
 */
class UserWorkout extends Model
{
    protected $table = 'userworkout';
    public $timestamps = true;

    // Fillable fields
    protected $fillable = [
        'user_id',
        'workout_id',
        // 'status',
        'start_date', // TODO: Add to db
        'completion_date',
        'days', // TODO: add to db (e.g. monday, tuesday, wednesday, etc.) then just check if current date is in array
        'feedback_rating',
        'user_comments',
        'created_at',
        'updated_at'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    public function workout()
    {
        return $this->belongsTo(Workout::class, 'id', 'workout_id');
    }
}
