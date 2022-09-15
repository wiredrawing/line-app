<?php

namespace App\Rules;

use App\Models\Player;
use Exception;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Throwable;

class ApiTokenRule implements Rule
{

    // player_id object.
    private $player_id = null;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param $attribute
     * @param $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        try {
            if ($this->player_id === null) {
                logger()->error("ApiTokenRuleにわたされた player_idがNULLです.");
                return false;
            }
            $player = Player::where(["api_token" => $value])->find($this->player_id);
            if ($player === null) {
                throw new Exception("プレイヤーIDとAPIトークンが一致しません");
            }
            return true;
        } catch (Throwable $e) {
            logger()->error($e);
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The validation error message.';
    }

    /**
     * Set the player id.
     *
     * @param int $player_id
     * @return $this
     */
    public function setPlayerId(int $player_id): ApiTokenRule
    {
        $this->player_id = $player_id;
        return $this;
    }

}
