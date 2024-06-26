<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property string $circle_bg
 * @property string $circle_fg
 * @property int $order_column
 * @property bool $is_active
 * @property bool $is_top
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Project> $projects
 * @property-read int|null $projects_count
 * @method static \Database\Factories\CategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category ordered(string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCircleBg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCircleFg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereIsTop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCategory {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property bool $is_public
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Project> $projects
 * @property-read int|null $projects_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\CollectionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Collection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection query()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCollection {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $verification_code
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\DomainFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Domain newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Domain newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Domain query()
 * @method static \Illuminate\Database\Eloquent\Builder|Domain whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Domain whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Domain whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Domain whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Domain whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Domain whereVerificationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Domain whereVerifiedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDomain {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $project_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Project $project
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\MaintainerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Maintainer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Maintainer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Maintainer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Maintainer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintainer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintainer whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintainer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintainer whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMaintainer {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property string|null $uuid
 * @property string $collection_name
 * @property string $name
 * @property string $file_name
 * @property string|null $mime_type
 * @property string $disk
 * @property string|null $conversions_disk
 * @property int $size
 * @property array $manipulations
 * @property array $custom_properties
 * @property array $generated_conversions
 * @property array $responsive_images
 * @property int|null $order_column
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $extension
 * @property-read mixed $human_readable_size
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $model
 * @property-read mixed $original_url
 * @property-read mixed $preview_url
 * @property-read mixed $type
 * @method static \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, static> all($columns = ['*'])
 * @method static \Database\Factories\MediaFactory factory($count = null, $state = [])
 * @method static \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|Media newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Media newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Media ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|Media query()
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereCollectionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereConversionsDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereCustomProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereGeneratedConversions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereManipulations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereResponsiveImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereUuid($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMedia {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $category_id
 * @property string $title
 * @property string $package_name
 * @property \App\Enums\ProjectTypeEnum $type
 * @property string $description
 * @property string $short_description
 * @property array $links
 * @property bool $is_recommended
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Collection> $collections
 * @property-read int|null $collections_count
 * @property-read \App\Models\Media|null $image
 * @property-read \App\Models\Release|null $latestRelease
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $maintainers
 * @property-read int|null $maintainers_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Release> $releases
 * @property-read int|null $releases_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Report> $reports
 * @property-read int|null $reports_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Media> $screenshots
 * @property-read int|null $screenshots_count
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\Review|null $userReview
 * @method static \Database\Factories\ProjectFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereIsRecommended($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereLinks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project wherePackageName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Project withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperProject {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $project_id
 * @property int $user_id
 * @property string $version_name
 * @property int $version_code
 * @property string|null $description
 * @property int $downloads_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Media|null $file
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Project $project
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\ReleaseFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Release newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Release newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Release query()
 * @method static \Illuminate\Database\Eloquent\Builder|Release whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Release whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Release whereDownloadsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Release whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Release whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Release whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Release whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Release whereVersionCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Release whereVersionName($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperRelease {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property \App\Enums\ReportTypeEnum $type
 * @property string $reportable_type
 * @property int $reportable_id
 * @property string $description
 * @property string|null $reviewed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $is_reviewed
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $reportable
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\ReportFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report query()
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereReportableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereReportableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereReviewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperReport {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $project_id
 * @property int $user_id
 * @property string $title
 * @property string $description
 * @property int $score
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Project $project
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Report> $reports
 * @property-read int|null $reports_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\ReviewFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Review newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Review newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Review onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Review query()
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Review withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperReview {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $username
 * @property string $provider_id
 * @property \App\Enums\AuthProviderEnum $provider
 * @property \Illuminate\Support\Carbon|null $username_changed_at
 * @property bool $is_admin
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Collection> $collections
 * @property-read int|null $collections_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Domain> $domains
 * @property-read int|null $domains_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Project> $maintaining
 * @property-read int|null $maintaining_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Project> $projects
 * @property-read int|null $projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Release> $releases
 * @property-read int|null $releases_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsernameChangedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

