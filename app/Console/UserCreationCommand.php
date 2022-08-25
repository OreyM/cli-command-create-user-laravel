<?php

namespace App\Console;

use App\Console\Traits\CliValidator;
use App\Exceptions\ValidationCliException;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserCreationCommand extends Command
{
    use CliValidator;

    protected $signature = 'user:create
        {name? : Username of the Admin to be created.}
        {email? : Email of the Admin to be created.}
        {password? : Password of the Admin to be created.}';

    protected $description = 'Create an admin for Administration System.';

    /**
     * @var Model
     */
    private $model = User::class;

    /**
     * @return void
     */
    public function handle(): void
    {
        try {
            $name = $this->getOptionData('name', ['name', ['required', 'min:3']], 'Enter name');
            $email = $this->getOptionData(
                'email',
                ['email', ['required', 'email', 'unique:users,email']],
                'Enter email'
            );
            $password = $this->getOptionData(
                'password',
                ['password', ['required', Password::min(8)->mixedCase()->letters()->symbols()->numbers()]],
                'Enter password',
                true
            );

            $this->createUser([
                'name'     => $name,
                'email'    => $email,
                'password' => $password,
            ]);
        } catch (\Exception $e) {
            $this->error($e->getMessage());

            return;
        }

        $this->info(sprintf('New Admin [name: %s, email: %s] created successfully!', $name, $email));
    }

    /**
     * @param string $argument
     * @param array $rules
     * @param string $question
     * @param bool $isHash
     * @return string
     * @throws ValidationCliException
     */
    private function getOptionData(string $argument, array $rules, string $question, bool $isHash = false): string
    {
        $option = $this->validate(
            fn () => $this->argument($argument) ?? ($isHash ? $this->secret($question) : $this->ask($question)),
            $rules
        );

        return $isHash ? Hash::make($option) : $option;
    }

    /**
     * @param array $input
     * @return void
     */
    private function createUser(array $input): void
    {
        (new $this->model)->create($input);
    }
}
