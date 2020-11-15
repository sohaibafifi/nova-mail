<?php

namespace KirschbaumDevelopment\NovaMail\Nova;

use App\Nova\User;
use Laravel\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use KirschbaumDevelopment\NovaMail\Models\NovaSentMail as NovaSentMailModel;

class NovaSentMail extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = NovaSentMailModel::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    public static $globallySearchable = false;


    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'subject',
        'content',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            MorphTo::make('mailable')->hideFromIndex(),
            BelongsTo::make(__('Sender'), 'sender', User::class),
            Text::make(__('Subject'), 'subject'),
            BelongsTo::make('Template', 'mailTemplate', NovaMailTemplate::class)->required()->rule(['required']),
            Textarea::make(__('Content'), 'content')
                ->displayUsing(function ($content) {
                    return trim(strip_tags($content));
                })
                ->alwaysShow(),
            DateTime::make(__('Sent At'), 'created_at')->format('M/d/Y h:mm:ss a'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Sent Mail';
    }

    /**
     * Determine if this resource is available for navigation.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return bool
     */
    public static function availableForNavigation(Request $request)
    {
        return config('nova_mail.show_resources.nova_sent_mail');
    }
}
