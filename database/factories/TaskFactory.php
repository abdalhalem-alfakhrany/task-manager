<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $adjectives = ["Quick", "Urgent", "Important", "Routine", "Pending", "Major", "Minor", "Critical", "Simple", "Complex"];
        $nouns = ["Report", "Meeting", "Review", "Task", "Update", "Call", "Email", "Plan", "Design", "Test"];
        $actions = ["Complete the", "Schedule the", "Review the", "Prepare the", "Finalize the", "Initiate the", "Organize the", "Document the", "Analyze the", "Submit the"];
        $objects = ["project documentation", "team meeting", "feature update", "client call", "test plan", "design review", "weekly report", "deployment", "code review", "budget analysis"];

        $title = $this->faker->randomElement($adjectives) . ' ' . $this->faker->randomElement($nouns);
        $description = $this->faker->randomElement($actions) . ' ' . $this->faker->randomElement($objects) . '.';

        $daysToAdd = $this->faker->numberBetween(2, 5);
        $randomHour = $this->faker->numberBetween(0, 23);
        $dueDate = now()->addDays($daysToAdd)->setHour($randomHour)->setMinute(0)->setSecond(0);

        return [
            'title' => $title,
            'description' => $description,
            'due_date' => $dueDate,
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
            'user_id' => $this->faker->numberBetween(3, 12),
        ];
    }
}
