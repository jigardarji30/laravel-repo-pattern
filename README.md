## Introduction

Laravel Repository Pattern is a package for make code in well structured form. It helps you separate logics in services and call database related queries into repository. It will help you to organized your logic code and query and make it easier to maintain.

## How to install

Use composer to install Laravel Repository Pattern Package

> composer require jigardarji/laravel-repo-pattern

### Generate Repository Pattern files by using following command

> php artisan make:repo_structure {name}

> Ex: php artisan make:repo_structure Test

it will generate following files

- Http
  - Resources
    - BaseResource.php
- Repositories
  - Test(folder)
    - TestInterface.php
    - TestRepository.php
  - RepositoryServiceProvider.php
- Services
  - Test(folder)
    - Providers
      - TestServicesProvider.php
    - TestServices.php

Register RepositoryServiceProvider.php and TestServicesProvider.php into config/app.php in provider section

```
'providers' => [
....
App\Repositories\RepositoryServiceProvider.php
App\Services\Test\Providers\TestServicesProvider.php
],
```

After register both file include service file into controller using \_\_construct method.

### TestController.php

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Test\TestServices;

class TestController extends Controller
{
    public $testServices;

    public function __construct(TestServices $testServices)
    {
        $this->testServices = $testServices;
    }

    public function index()
    {
        $data = $this->testServices->index();
        return json_decode($data);
    }
}
```

### TestService.php

```
<?php

namespace App\Services\Test;

use App\Repositories\Test\TestInterface;

class TestServices
{
    private $testRepo;

    public function __construct(TestInterface $testRepo)
    {
        $this->testRepo = $testRepo;
    }

    public function index(array $params)
    {
        /**
         * Business logic
         */
        return $this->testRepo->getData();
    }
}
```

### TestInterface.php

Define all method with param you want to create and implement all defined method into repository file.

```
<?php

namespace App\Repositories\Test;

interface TestInterface
{
    public function index(array $fields);
}
```

### TestRepository.php

In TestRepository file you need to define model in construct method and write query into different methods.

```
<?php

namespace App\Repositories\Test;

use App\Models\Test;
use App\Repositories\Test\TestInterface;

class TestRepository implements TestInterface
{

    private $test;

    public function __construct(Test $test)
    {
        $this->test = $test;
    }

    public function index(array $field)
    {
        return $this->test->select('id', 'name')->get();
    }
}
```

If you add another model into construct method you have to define that model into RepositoryServiceProvider.php as well also if you create another structure for another module you have to add code into RepositoryServiceProvider.php

### RepositoryServiceProvider.php

```
<?php

namespace App\Repositories;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Test\TestInterface;
use App\Repositories\Test\TestRepository;
use Illuminate\Contracts\Support\DeferrableProvider;

class RepositoryServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $this->registerTestRepository();
        // create new function below and define here for new module
    }

    protected function registerTestRepository()
    {
        $this->app->bind(TestInterface::class, function () {
            return new TestRepository(new Test(),'add new model as per repository file');
        });
    }

    public function provides()
    {
        return [
            TestInterface::class,
            // add new interface which you created and define above
        ];
    }
}
```

### BaseResource.php

if you are making apis you can use this file to send response of api in well formed. it need to use in controller to return success or error response from controller.

```
<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{

    public function success($data, $code = 200)
    {
        $response =  [
            'status' => 'success',
            'data' => $data,
            'errors' => null
        ];
        return response()->json($response, $code);
    }

    public function error($messages, $code = 500)
    {
        $response = [
            'status' => 'error',
            'data' => null,
            'errors' => $messages
        ];
        if (!empty($messages)) {
            $response['errors'] = $messages;
        } else {
            $response['errors'] = 'Something went wrong!';
        }
        return response()->json($response, $code);
    }
}
```

if you face any error you can fix it by using following command

> composer dump-autoload
