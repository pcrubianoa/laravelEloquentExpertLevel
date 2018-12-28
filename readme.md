# Class Curriculum - laraveldaily (26 Dec 2018)

## Eloquent Model Options and Settings
 - Artisan Command make:model with (hidden) options 
 - Singular or Plural? What about multiple words? 
 - Saving a Model: $fillable or $guarded? 
 - Properties for Tables, Keys, Increments, Pages and Dates 

## Create/Update in Eloquent
 -  "Magic" methods: FirstOrCreate() and other 2-in-1s 
 -  Model Observers: "listening" to record changes 
 -  Accessors and Mutators: Change Model Values 
 -  Database Seeds and Factories: Prepare Dummy Data
 -  Seeds and Factories with Relationships 
 -  Check Methods/Properties in Eloquent API Docs 


## Querying and Filtering Data Effectively
 -  Advanced find() and all(): Methods and Parameters 
 -  WhereX Magic Methods for Fields and Dates 
 -  Brackets to Eloquent: (A and B) or (C and D) 
 -  Query Scopes: Where Conditions Applied Globally
 -  Eloquent when(): More Elegant if-statement 
 -  Ordering by Relationship: orderBy vs sortBy 
 -  Raw Database Queries with Examples 


## Eloquent Collections and their Methods
 - Why You Need Collections and How to Use Them 
 - Methods for Fetching and Transforming 
 - Methods for Filtering with Callbacks 
 - Methods for Math Calculations 
 - Methods for Debugging 
|

## Advanced Eloquent Relationships
 - Polymorphic Relations Explained 
 - Polymorphic Many-to-Many Relations 
 - w Advanced Pivot Tables in Many-to-Many 
 - HasManyThrough Relations 
 - Creating Records with Relationships 
 - Querying Records with Relationships 


## Eloquent Performance
 - Laravel Debugbar: How to Measure Performance 
 - Performance Test: Eloquent vs Query Builder vs SQL 
 - N+1 Problem and Eager Loading: Be Careful with Eloquent 
 - Caching in Eloquent 


## Useful Packages to Extend Eloquent
 - spatie/laravel-medialibrary: Associate files with Eloquent models 
 - dimsav/laravel-translatable: Package for Multilingual Models
 - spatie/eloquent-sortable: Sortable Eloquent Models 
 - spatie/laravel-tags: Add Tags and Taggable Behavior 
 - owen-it/laravel-auditing: Record the Changes From Models 
 - michaeldyrynda/laravel-cascade-soft-deletes: Cascade Delete & Restore 


## Artisan Command make:model with (hidden) options 

 Create a model instance:
 
    php artisan make:model Post

 Create a model instance with migration:

    php artisan make:model Post --migration
    php artisan make:model Post -m

 Create a model instance with migration and controller:

     php artisan make:model Post -mc

 Create a model instance with migration and resource controller:

     php artisan make:model Post -mcr

 Create a model instance with migration,resource controller and factory:

    php artisan make:model Post -mcrf

Shorcut to create a model instance with migration,resource controller and factory:

    php artisan make:model Post -a

Display and describe the command's available arguments and options:

    php artisan make:model --help


## Singular or Plural? What about multiple words?

What | How | Good | Bad
------------ | ------------- | ------------- | -------------
Controller | singular | ArticleController | ~~ArticlesController~~
Model | singular | User | ~~Users~~
Table | plural | article_comments | ~~article_comment, articleComments~~
Migration | - | 2017_01_01_000000_create_articles_table | ~~2017_01_01_000000_articles~~

Read more naming conventions [Laravel best practices](https://github.com/alexeymezenin/laravel-best-practices/blob/master/README.md#follow-laravel-naming-conventions)