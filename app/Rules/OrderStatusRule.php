<?php

namespace App\Rules;

use App\Enums\JobTypeEnums;
use App\Enums\OrderStatusEnums;
use Illuminate\Contracts\Validation\Rule;

class OrderStatusRule implements Rule
{
    protected $availableStatuses = [];
    protected $errorMessage = null;

    public function __construct()
    {

    }

    public function passes($attribute, $value)
    {
        $allEnums = JobTypeEnums::all();

        foreach ($allEnums as $enum) {
            if (isset($enum['view_only_order_statuses'])) {
                $this->availableStatuses = array_keys($enum['view_only_order_statuses']);
                break;
            }
        }

        if (!in_array($value, $this->availableStatuses)) {
            $statusNames = [];
            foreach ($this->availableStatuses as $statusId) {
                $statusName = OrderStatusEnums::getConstantByName($statusId);
                if ($statusName !== null) {
                    $statusNames[] = $statusName;
                }
            }

            $this->errorMessage = "The selected status '" . OrderStatusEnums::getConstantByName($value) . "' is invalid. Available statuses: " . implode(', ', $statusNames);
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMessage ?? 'The selected status is invalid.';
    }
}
