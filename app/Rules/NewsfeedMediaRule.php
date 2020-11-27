<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NewsfeedMediaRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->message = "The type of mediaList is must be array";
        $this->mediaCount = 0;
        $this->newsfeedMediaMaxUploadLimit = config('media.newsfeed.maxUploadLimit');
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
        if(is_array($value))
        {
            foreach ($value as $media) {

                if(is_array($media))
                {
                    if(array_key_exists("data", $media) && array_key_exists("type", $media))
                    {
                        if($media["data"] != null && $media["type"] != null)
                        {
                            $this->mediaCount++;
                        }
                        else{
                            $this->message = "Media's data value and type value are required.";
                            return false;
                        }
                    }
                    else{
                        $this->message = "Media's data key and type key are required.";
                        return false;
                    }
                }
                
            }

            if($this->mediaCount <= $this->newsfeedMediaMaxUploadLimit)
            {
                return true;
            }
            else{
                $this->message = config('message.overNewsfeedMediaUploadLimit');
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
