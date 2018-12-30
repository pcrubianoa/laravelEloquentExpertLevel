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

# Eloquent Model Options and Settings

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


## Saving a Model: $fillable or $guarded? 

Mass assignment: means to send an array to the model to directly create a new record in Database.

$fillable specifies which attributes in the table should be mass-assignable.

    <?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class Article extends Model
    {
        /**
        * The attributes that are mass assignable.
        *
        * @var array
        */
        protected $fillable = ['title', 'article_text'];
    }

$guarded specifies which attributes in the table shouldn't be mass-assignable.
    
    <?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class Article extends Model
    {
        /**
        * The attributes that are mass assignable.
        *
        * @var array
        */
        protected $guarded = ['id'];
    }

## Properties for Tables, Keys, Increments, Pages and Dates 

`$table` specifies a custom table.

`$primaryKey` specifies a custom primary key. 

`$incrementing` specifies a non-incrementing or a non-numeric primary key.

`$perPage` specifies the number of items per page in paginate.

`$timestamps` disable created_at and updated_at columns.

`CREATED_AT` and `UPDATED_AT` specify the custom names of the columns used to store the timestamps.

`$dateFormat` specifies the custom format of your timestamps.

`$dates` converts columns to instances of Carbon.

    <?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class Article extends Model
    {
        /**
        * The attributes that are mass assignable.
        *
        * @var array
        */
        protected $table = 'user_articles';

        protected $primaryKey = 'article_id';

        public $incrementing = false;

        $perPage = 5;

        public $timestamps = false;

        const CREATED_AT = 'creation_date';

        const UPDATED_AT = 'last_update';

        protected $dateFormat = 'm/d/Y H:i:s';

        protected $dates = [
            'created_at',
            'updated_at',
            'deleted_at'
        ];
                
    }

# Create/Update in Eloquent

## "Magic" methods: FirstOrCreate() and other 2-in-1s

There are two other methods you may use to create models by mass assigning attributes:  firstOrCreate and firstOrNew. The firstOrCreate method will attempt to locate a database record using the given column / value pairs. If the model can not be found in the database, a record will be inserted with the attributes from the first parameter, along with those in the optional second parameter. [Laravel docs](https://laravel.com/docs/5.7/eloquent#other-creation-methods)

    function store(Request $request)
    {
        $article = Article::where('title', $request->title)->first();
        if(!$article){
            $article = Article::create([
                'title' => $request->title,
                'article_text' => $request->article_text
            ]);
        }

        // some actions with the article
        $article->chapters()->create($request->chapters);
    }

It can be replaced by:

    function store(Request $request)
    {
        $article = Article::firstOrCreate(['title' => $request->title], ['article_text' => $request->article_text]);

        // some actions with the article
        $article->chapters()->create($request->chapters);
    }

    function store(Request $request)
    {
        $article = Article::firstOrNew(['title' => $request->title], ['article_text' => $request->article_text]);
        $article->field = $value;
        $article->save();

        // some actions with the article
        $article->chapters()->create($request->chapters);
    }

`updateOrCreate` updates an existing model or create a new model if none exists.

    function store(Request $request)
    {
        $article = Article::where('title', $request->title)->where('user_id', auth()->id()->first();
        if($article){
            $article->update(['article_text' => $request-article_text]);
        }else{
            $article = Article::create([
                'title' => $request->title,
                'article_text' => $request->article_text.
                'user_id' => auth()->id
            ]);
        }

        $article = Article::updateOrCreate(['title' => $request->title, 'user_id' => auth()->id()]);

        // some actions with the article
        $article->chapters()->create($request->chapters);
    }

It can be replaced by:

        function store(Request $request)
    {
        $article = Article::updateOrCreate(['title' => $request->title, 'user_id' => auth()->id()],
        ['article_text' => $request->article_text]);

        // some actions with the article
        $article->chapters()->create($request->chapters);
    }

## Model Observers: "listening" to record changes 

Observers are used to group event listeners for a model, for create a new observer run:

    php artisan make:observer ArticleObserver --model=Article

Register the observer in the `AppServiceProvider`:

    <?php

    namespace App\Providers;

    use App\Article;
    use App\Observers\UserObserver;
    use Illuminate\Support\ServiceProvider;

    class AppServiceProvider extends ServiceProvider
    {
        /**
        * Bootstrap any application services.
        *
        * @return void
        */
        public function boot()
        {
            Article::observe(ArticleObserver::class);
        }

        /**
        * Register the service provider.
        *
        * @return void
        */
        public function register()
        {
            //
        }
    }

Article model:

    public function store(Request $request)
    {
        Article::create($request->all());
        return redirect()->route('articles.index');
    }

ArticleObserver:

    public function createdArticle(Article $article)
    {
        info('Article is saved');
    }

Every time an article is created, we will have a message in log.

    [2018-09-19 20:15:26] local.INFO: Article is saved

## Accessors and Mutators: Change Model Values 

Accessors and mutators allow you to format Eloquent attribute values when you retrieve or set them on model instances. 

Accesor -> get Attribute
Mutator -> set Attribute

Create a `getFullNameAttribute` method on model:

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->surname;
    }

To access the value of the accessor, you may access the  `first_name` attribute on a model instance:

    <td>{{ $user->full_name }}</td>


    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }

to set the first_name attribute to Taylor:

    $user->first_name = 'taylor';

## Database Seeds and Factories: Prepare Dummy Data

### Seeders

Seeding your database with test data using seed classes. Create seeder run:

    php artisan make:seeder UsersTableSeeder

add a database insert statement to the run method:

    <?php

    use Illuminate\Database\Seeder;

    class UserTableSeeder extends Seeder
    {
        /**
        * Run the database seeds.
        *
        * @return void
        */
        public function run()
        {
            \App\User::create('users')->insert([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('password'),
            ]);
        }
    }

Register seeder in `DatabaseSeeder`:

    <?php

    use Illuminate\Database\Seeder;

    class DatabaseSeeder extends Seeder
    {
        /**
        * Seed the application's database.
        *
        * @return void
        */
        public function run()
        {
            $this->call(UsersTableSeeder::class);
        }
    }

Finally we run seeder :

    php artisan db:seed

### Model Factories

Factories generate large amounts of database records. Laravel brings by default the `UserFactory` with the data to declare a user:
Laravel uses Faker that is a PHP library that generates fake data. [Read more](https://github.com/fzaninotto/Faker)

    <?php

    use Faker\Generator as Faker;


    $factory->define(App\User::class, function (Faker $faker) {
        return [
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token' => str_random(10),
        ];
    });

then we call factory in  `UserTableSeeder`. We can also specify the number of records:

    <?php

    use Illuminate\Database\Seeder;

    class UserTableSeeder extends Seeder
    {
        /**
        * Run the database seeds.
        *
        * @return void
        */
        public function run()
        {
            factory(App\User::class, 50)->create();
        }
    }

## Seeds and Factories with Relationships

Define relationship:

    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('article_text');
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

User factory:

    <?php

    use Faker\Generator as Faker;


    $factory->define(App\User::class, function (Faker $faker) {
        return [
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token' => str_random(10),
        ];
    });

Articles factory:

    <?php

    use Faker\Generator as Faker;


    $factory->define(App\User::class, function (Faker $faker) {
        return [
            'title' => $faker->text(50),
            'article_text' => $faker->text(500),
        ];
    });

In `UsertableSeeder` use saveMany method:

    <?php

    use Illuminate\Database\Seeder;

    class UserTableSeeder extends Seeder
    {
        /**
        * Run the database seeds.
        *
        * @return void
        */
        public function run()
        {
            factory(App\User::class, 50)->create()->each(function($user){
                $user->articles()->saveMany(factory(App\Article::class, 3)->make());
            });
        }
    }

## Check Methods/Properties in Eloquent API Docs 

En la documentacion se encuentra toda la informacion del API de  eloquent y que podemos trabajar desde los modelos. 
una descripcion, los metodos y el tipo de valor que retornan, los parametros que reciben y Traits disponibles.

https://laravel.com/api/5.7/Illuminate/Database/Eloquent/Model.html


