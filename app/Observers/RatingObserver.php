<?php
namespace App\Observers;

use App\Models\Rating;

class RatingObserver
{
    public function created(Rating $rating)
    {
        $this->updateRateableAverage($rating);
    }

    public function updated(Rating $rating)
    {
        $this->updateRateableAverage($rating);
    }

    public function deleted(Rating $rating)
    {
        $this->updateRateableAverage($rating);
    }


    protected function updateRateableAverage(Rating $rating)
    {
        $rateable = $rating->rateable;

        if (!$rateable) {
            return;
        }

      
        if (in_array('rate', $rateable->getFillable())) {
            $rateable->update([
                'rate' => $rateable->ratings()->avg('rating'),
            ]);
        }
    }
}
