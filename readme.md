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

Every time an article is created, you will have a message in log.

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

Finally you run seeder :

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

then you call factory in  `UserTableSeeder`. You can also specify the number of records:

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

In the documentation you will find all the information of the eloquent API and that you can work from the models.
a description, the methods and the type of value that they return, the parameters they receive and Traits available.

https://laravel.com/api/5.7/Illuminate/Database/Eloquent/Model.html

# Querying and Filtering Data Effectively

## Advanced find() and all(): Methods and Parameters 

`all(array|mixed $columns = ['*'])`
Get all of the models from the database.

    $user = User::all();
    $user = User::all(['name', 'id']);

`find(array|mixed $columns = ['*'])`
these methods return a single model instance.

    $user = User::find(1);
    $user = User::find(1, 2);
    $user = User::find([1, 2, 3], ['name']);

    $user = User::findOrFail(1);
    $user = User::where('email', 'example@mail.com')->firstOrFail();

## WhereX Magic Methods for Fields and Dates 

    $user = User::where('email', 'example@mail.com')->firstOrFail();
    $user = User::whereEmail('example@mail.com')->get();

    $user = User::where('created_at', '2019-12-27 11:28:29')->get();
    $user = User::whereDate('created_at', '2019-12-27')->get();
    $user = User::whereYear('created_at', '2019')->get();
    $user = User::whereMonth('created_at', '09')->get();
    $user = User::whereDay('created_at', '20')->get();
    $user = User::whereTime('created_at', '20:15:05')->get();
    $user = User::whereCreatedAt('20:15:05')->get();

    More wherre cluses: 
    whereBetween
    whereNotBetween
    whereIn / whereNotIn
    whereNull / whereNotNull
    whereDate / whereMonth / whereDay / whereYear / whereTime
    whereColumn

    https://laravel.com/docs/5.7/queries#where-clauses

 ## Brackets to Eloquent: (A and B) or (C and D) 

if you have and-or mix in SQL query, like this:

    public function index()
    {
        $articles = Article::where('user_id', 1)
        ->whereYear('created_at', 2018)
        ->orwhereYear('update_at', 2018)
        ->get();

        return view('articles.index', compact('articles'));
    }
You can display raw query sql with `toSql()` method:

    public function index()
    {
        $articles = Article::where('user_id', 1)
        ->whereYear('created_at', 2018)
        ->orwhereYear('update_at', 2018)
        ->toSql();
        dd($articles);

        return view('articles.index', compact('articles'));
    }

You have the follow result. The order will be incorrect.

    "select * from `articles` where `user_id` = ? and year(`created_at`) = ? or year(`updated_at`) = ?"

The right way is using closure functions as sub-queries:

    public function index()
    {
        $articles = Article::where('user_id', 1)
        ->where(function($query){
        return $query->whereYear('created_at', 2018)
            ->orwhereYear('update_at', 2018);
        })->get();

        return view('articles.index', compact('articles'));
    }

    public function index()
    {
        $articles = Article::where('user_id', 1)
        ->where(function($query){
        return $query->whereYear('created_at', 2018)
            ->orwhereYear('update_at', 2018);
        })->toSql();
        dd($articles);

        return view('articles.index', compact('articles'));
    }

Now, the result 

    "select * from `articles` where `user_id` = ? and (year(`created_at`) = ? or year(`updated_at`) = ?)"

## Query Scopes: Where Conditions Applied Globally

Global scopes allow you to add constraints to all queries for a given model.

    public function index()
    {
        $articles = Article::where('created_at', '>', now()->subDays(30)->get());
        return view('articles.index', compact('articles'));
    }

    public function search(Reques $request)
    {
        $articles = Article::where('created_at', '>', now()->subDays(30))
            ->where('user_id', $request->user_id)
            ->get();

        return view('articles.index', compact('articles'));
    }


    <?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class Article extends Model
    {
        protected $fillable = ['title', 'article_text', 'user_id'];

        public function scopeNewest($query)
        {
            return $query->where('created_at', '>', now()->subDays(30));
        }
    }


    public function index()
    {
        $articles = Article::newest()->get());
        return view('articles.index', compact('articles'));
    }

    public function search(Reques $request)
    {
        $articles = Article::newest()
            ->where('user_id', $request->user_id)
            ->get();

        return view('articles.index', compact('articles'));
    }

Global scopes

    public function index()
    {
        $articles = Article::where('user_id', 1)->get());
        return view('articles.index', compact('articles'));
    }

    public function search(Reques $request)
    {
        $articles = Article::where('user_id', 1)
            ->where('user_id', $request->user_id)
            ->get();

        return view('articles.index', compact('articles'));
    }

    public function edit($article_id)
    {
        $article = Article::where('user_id', 1)
        ->find($article_id);
        return view('articles.index', compact('article'));
    }


    <?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Builder;

    class Article extends Model
    {
        /**
        * The "booting" method of the model.
        *
        * @return void
        */
        protected static function boot()
        {
            parent::boot();

            static::addGlobalScope('user_filter', function (Builder $builder) {
                $builder->where('user_id', 1);
            });
        }
    }


        public function index()
    {
        $articles = Article::all();
        return view('articles.index', compact('articles'));
    }

    public function search(Reques $request)
    {
        $articles = Article::where('user_id', $request->user_id)
            ->get();

        return view('articles.index', compact('articles'));
    }

    public function edit($article_id)
    {
        $article = Article::find($article_id);
        return view('articles.index', compact('article'));
    }


    <?php

    namespace App\Scopes;

    use Illuminate\Database\Eloquent\Scope;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Builder;

    class ArticleUserScope implements Scope
    {
        /**
        * Apply the scope to a given Eloquent query builder.
        *
        * @param  \Illuminate\Database\Eloquent\Builder  $builder
        * @param  \Illuminate\Database\Eloquent\Model  $model
        * @return void
        */
        public function apply(Builder $builder, Model $model)
        {
            $builder->where('user_id', 1);
        }
    }


    <?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Builder;

    class Article extends Model
    {
        /**
        * The "booting" method of the model.
        *
        * @return void
        */
        protected static function boot()
        {
            parent::boot();

            static::addGlobalScope(new ArticleUserScope);
        }
    }

## Eloquent when(): More Elegant if-statement 

if you have the following logic with if conditinal:

    <?php

    namespace App\Http\Controllers;

    use App\Article;
    use Illuminate\Http\Request;

    class ArticleController extends Controller
    {
        public function index()
        {
            $articles = Article::all();
            return view('articles.index', compact('articles'));
        }

        public function search()
        {
            $query = Article::query();
            if(request('user_id')){
                $query->where('user_id', request('user_id'));
            }
            if(request('title')){
                $query->where('title', request('title'));
            }
            $articles = $query->get();

            return view('articles.index', compact('articles'));
        }
    }

You can use the when method to replace the conditional if:

    <?php

    namespace App\Http\Controllers;

    use App\Article;
    use Illuminate\Http\Request;

    class ArticleController extends Controller
    {
        public function index()
        {
            $articles = Article::all();
            return view('articles.index', compact('articles'));
        }

        public function search()
        {
            $articles = Article::when(requet('user_id'), function($query){
                return $query->where('user_id', request('user_id'));
            })->when(request('title'), function($query){
                return $query->where('title', request('title'))
            })->get();


            return view('articles.index', compact('articles'));
        }
    }

## Ordering by Relationship: orderBy vs sortBy 

The orderBy method orders elements by the given key:

    $articles = Article::all()->orderBy("name");
    $articles = Article::orderBy('name')->get();

You can replace the `orderBy()` method by `shortBy()`:

        public function index()
        {
            $user = User::orderBy('name')->get();
            return view('users.index', compact('users'))
        }

You can use the collections with `all()` and use the sortBy method, by default `sortBy` orders the elements ascending:

        public function index()
        {
            $user = User::all()->sortBy('days_active');
            return view('users.index', compact('users'))
        }

to order the elements in descending order you use `sortByDesc()`:

        public function index()
        {
            $user = User::all()->sortByDesc('days_active');
            return view('users.index', compact('users'))
        }

## Raw Database Queries with Examples

To create a raw expression, you may use the DB::raw method:

    public function index()
    {
        $users = User::select(DB::raw('id, name, email, created_at, DATEDIFF(updated_at, created_at) as days_Active))->get();

        return view('users.index', compact('users'));
    }

    public function index()
    {
        $users = User::selectRaw('id, name, email, created_at, DATEDIFF(updated_at, created_at) as days_Active)->get();

        return view('users.index', compact('users'));
    }

    public function index()
    {
        $users = User::select(DB::raw('id, name, email, created_at, DATEDIFF(updated_at, created_at) as days_Active))
        ->whereRaw('DATEDIFF(updated_at, created_at) > 300')
        ->get();

        return view('users.index', compact('users'));
    }

    public function index()
    {
        $users = User::select(DB::raw('id, name, email, created_at, DATEDIFF(updated_at, created_at) as days_Active))
        ->orderByRaw('DATEDIFF(updated_at, created_at) desc')
        ->get();

        return view('users.index', compact('users'));
    }

# Eloquent Collections and their Methods

## Why You Need Collections and How to Use Them 

Laravel collections are one of the most powerful provisions of the Laravel framework. They are what PHP arrays should be, but better. (scotch.io)
The Eloquent collection object extends the Laravel base collection, so it naturally inherits dozens of methods used to fluently work with the underlying array of Eloquent models.

A small example:

    public function index()
    {
        $articles = Articles::all();

        $titles = [];
        foreach ($articles as $aticle){
            if(strlen($article->title) > 40){
                $titles[] = $article->title;
            }
        }

        dd($articles->filter(function($article){
            return strlen($article->title) > 40;
        })->map(function($article){
            return $article->title;
        }));

        return view('articles.index', compact('titles'));
    }

## Methods for Fetching and Transforming 

Some examples of the use of some collections methods:

Controller:

    public function index()
    {
        $articles = Article::all();
        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        $users = User::select(['name', 'id'])->get()
        ->prepend(new User(['name' => '-- Please choose author --']));
        return view('articles.create', compact('users'));
    }

View:

    <select name="user_id" class="form-control">
        @foreach
            <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
    </select>

The shuffle method randomly shuffles the items in the collection:

    $users = User::select(['name', 'id'])->get()->shuffle();

The pluck method retrieves all of the values for a given key:

    $users = User::pluck('name', 'id');

The chunk method breaks the collection into multiple, smaller collections of a given size:

    $users = User::select(['name', 'id'])->get()->shuffle()->chunk(3);

The random method returns a random item from the collection:

    $users = User::select(['name', 'id'])->get()->random();

The contains method determines whether the collection contains a given item:

    $users = User::select(['name', 'id'])->get()->random();
    if ($users->contains('password', '$2y$10$TydfRTyjLAVB834GnsaY'))
        dd('Not ready');

[More available methods](https://laravel.com/docs/5.7/collections#available-methods)

## Methods for Filtering with Callbacks

    public function index()
    {
        $users = User::all()->each(function($user) {
            if ($users->contains('password', '$2y$10$TydfRTyjLAVB834GnsaY')) {
                info('User ' . $user->email . 'has not changed password');
            }
        });

        $names = User::all()->map(function ($user) {
            return strlen($user->name);
        });

        $names = User::all()->filter(function ($user) {
            return strlen($user->name) > 17;
        });

        $names = User::all()->reject(function ($user) {
            return strlen($user->name) > 17;
        });
    }

 ## Methods for Math Calculations 

    public function index()
    {
        $articles = Article::all();
        echo 'Total articles' . $articles->count() . '<hr />';
        echo 'Total word written: ' . $articles->sum('word_count') . '<hr />';
        echo 'Minimun word count: ' . number_format($articles->min('word_count', 2)) . '<hr />';
        echo 'Maximun word count: ' . number_format($articles->max('word_count', 2)) . '<hr />';
        echo 'Average word count: ' . number_format($articles->avg('word_count', 2)) . '<hr />';
        echo 'Median word count: ' . number_format($articles->median('word_count', 2)) . '<hr />';
        echo 'Most often word count: ' . implode(', ' $articles->mode('word_count')) . '<hr />';
    }

Calculations:

count()
The count method returns the total number of items in the collection.

avg()
The avg method returns the average value of a given key.

max()
The max method returns the maximum value of a given key.

median()
The median method returns the median value of a given key.

min()
The min method returns the minimum value of a given key.

sum()
The sum method returns the sum of all items in the collection.

## Methods for Debugging

dd()
The dd method dumps the collection´s items and ends execution of the script.

dump()
The dump method dumps the collection´s items.

tap()
The tap method passes the collection to the given callback, allowing you to "tap" into the collection at a specific point and do something with the items.

    public function index()
    {
        $users = User::select(['name', 'id'])
            ->take(5)
            ->get()
            ->shuffle()
            ->chunk(3);
        dd($users);
    }

    public function index()
    {
        $users = User::select(['name', 'id'])
            ->take(5)
            ->get()
            ->shuffle()
            ->tap(function ($users) {
                info($users->first());
            })
            ->chunk(3);
    }