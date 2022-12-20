<?php

namespace App\Observers;

use App\Models\Events;
use Carbon\Carbon;

class EventRecurrenceObserver
{
    /**
     * Handle the Events "created" event.
     *
     * @param  \App\Models\Events  $events
     * @return void
     */
    public function created(Events $events)
    {
        if (!$events->event()->exists()) {
            $recurrences = [
                'Daily'     => [
                    'times'     => 365,
                    'function'  => 'addDay'
                ],
                'Weekly'    => [
                    'times'     => 52,
                    'function'  => 'addWeek'
                ],
                'Monthly'    => [
                    'times'     => 12,
                    'function'  => 'addMonth'
                ],
                'Yearly'    => [
                    'times'     => 1,
                    'function'  => 'addYear'
                ]
            ];
            $startTime = Carbon::parse($events->event_start_date);
            $endTime = Carbon::parse($events->event_end_date);
            $description = ($events->event_description);
            $recurrence = $recurrences[$events->event_recurrence_type] ?? null;

            if ($recurrence)
                for ($i = 0; $i < $recurrence['times']; $i++) {
                    $startTime->{$recurrence['function']}();
                    if ($startTime->lte($endTime)) {
                        $events->events()->create([
                            'event_title'          => $events->event_title,
                            'event_start_date'    => $startTime,
                            'event_end_date'      => $endTime,
                            'event_description'      => $description,
                            'event_recurrence_type'    => $events->event_recurrence_type,
                        ]);
                    }
                }
        }
    }

    /**
     * Handle the Events "updated" event.
     *
     * @param  \App\Models\Events  $event
     * @return void
     */
    public function updated(Events $event)
    {
        if ($event->events()->exists() || $event->event) {
            $startTime = Carbon::parse($event->getOriginal('event_start_date'))->diffInSeconds($event->event_start_date, false);
            $endTime = Carbon::parse($event->getOriginal('event_end_date'))->diffInSeconds($event->event_end_date, false);
            if ($event->event) {
                $childEvents = $event->event->events()->whereDate('event_start_date', '>', $event->getOriginal('event_start_date'))->get();
            } else {
                $childEvents = $event->events;
            }

            foreach ($childEvents as $childEvent) {
                if ($startTime) {
                    $childEvent->event_start_date = Carbon::parse($childEvent->event_start_date)->addSeconds($startTime);
                }
                if ($endTime) {
                    $childEvent->event_end_date = Carbon::parse($childEvent->event_end_date)->addSeconds($endTime);
                }
                if ($event->isDirty('event_title') && $childEvent->event_title == $event->getOriginal('event_title')) {
                    $childEvent->event_title = $event->event_title;
                }

                $childEvent->saveQuietly();
            }
        }
        if ($event->isDirty('event_recurrence_type') && $event->event_recurrence_type != 'none') {
            self::created($event);
        }
    }

    /**
     * Handle the Events "deleted" event.
     *
     * @param  \App\Models\Events  $events
     * @return void
     */
    public function deleted(Events $event)
    {
        if ($event->events()->exists())
            $events = $event->events()->pluck('id');
        else if ($event->event)
            $events = $event->event->events()->whereDate('event_start_date', '>', $event->event_start_date)->pluck('id');
        else
            $events = [];

        Events::whereIn('id', $events)->delete();
    }

    /**
     * Handle the Events "restored" event.
     *
     * @param  \App\Models\Events  $events
     * @return void
     */
    public function restored(Events $events)
    {
        //
    }

    /**
     * Handle the Events "force deleted" event.
     *
     * @param  \App\Models\Events  $events
     * @return void
     */
    public function forceDeleted(Events $events)
    {
        //
    }
}
