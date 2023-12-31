<?php

namespace App\Models;

/**
 * Provides a template and an interface for user data models.
 *
 * @copyright  2023 ModernFit-Group:4
 * @category   Models
 * @since      Class available since Release 1.0.2
 */
class WorkoutExercise extends Model
{

    public $table = 'workoutexercise';
    public $timestamps = true;

    // Fillable fields
    public $fillable = [
        'id',
        'workout_id',
        'exercise_id',
        'sets',
        'reps',
        'rest_time_between_sets',
        'created_at',
        'updated_at'
    ];

    // Relationships
    public function workout()
    {
        return $this->belongsTo(Workout::class, 'id', 'workout_id');
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class, 'id', 'exercise_id');
    }
}
