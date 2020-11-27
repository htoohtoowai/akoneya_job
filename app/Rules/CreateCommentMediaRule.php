<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CreateCommentMediaRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->message = "";
        $this->mediaCount = 0;
        $this->mediaTypeRequired = config('message.mediaTypeRequired');
        $this->mediaDataRequired = config('message.mediaDataRequired');
        $this->commentRequired = config('message.commentRequired');
        $this->mediaDataAndTypeRequired = config('message.mediaDataAndTypeRequired');
        $this->mediaDataAndTypeKeyRequired = config('message.mediaDataAndTypeKeyRequired');
        $this->overCommentMediaUploadLimit = config('message.overCommentMediaUploadLimit');
        $this->commentMediaMaxUploadLimit = config('media.newsfeedComment.maxUploadLimit');
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (is_array($value)) {
            foreach ($value as $media) {
                if (array_key_exists("data", $media) && array_key_exists("type", $media)) {
                    if ($media["data"] != null && $media["type"] != null) {
                        $this->mediaCount++;
                    } else {
                        $this->message =$this->mediaDataAndTypeRequired;
                        return false;
                    }
                } else {
                    $this->message = $this->mediaDataAndTypeKeyRequired;
                    return false;
                }
            }
            if ($this->mediaCount <= $this->commentMediaMaxUploadLimit) {
                return true;
            } else {
                $this->message = config('message.overCommentMediaUploadLimit');
            }
        }
        else{
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
