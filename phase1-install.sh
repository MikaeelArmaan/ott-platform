#!/usr/bin/env bash
set -e

echo "=== Installing OTT Phase 1 ==="

composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\\Sanctum\\SanctumServiceProvider"

mkdir -p app/Models app/Http/Controllers/Api/V1

cat > app/Models/Profile.php <<'PHP'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Profile extends Model {
 protected $fillable=['user_id','name','is_kids','maturity_level','pin_hash'];
 protected $casts=['is_kids'=>'boolean'];
}
PHP

cat > app/Models/Content.php <<'PHP'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Content extends Model {
 protected $fillable=['type','title','description','language','runtime_seconds','maturity_rating','is_published'];
 protected $casts=['is_published'=>'boolean'];
}
PHP

cat > app/Models/VideoAsset.php <<'PHP'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class VideoAsset extends Model {
 protected $fillable=['content_id','source_url','hls_master_url','status'];
}
PHP

cat > app/Models/WatchHistory.php <<'PHP'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class WatchHistory extends Model {
 public $timestamps=false;
 protected $fillable=['profile_id','content_id','position_seconds','duration_seconds','completed','last_watched_at'];
}
PHP

cat > app/Models/Watchlist.php <<'PHP'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Watchlist extends Model {
 public $timestamps=false;
 protected $fillable=['profile_id','content_id','created_at'];
}
PHP

cat > app/Http/Controllers/Api/V1/AuthController.php <<'PHP'
<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller {
 public function register(Request $r){
  $u=User::create([
   'name'=>$r->name,
   'email'=>$r->email,
   'password'=>Hash::make($r->password)
  ]);
  return ['token'=>$u->createToken('api')->plainTextToken];
 }
 public function login(Request $r){
  $u=User::where('email',$r->email)->first();
  if(!$u||!Hash::check($r->password,$u->password))
   return response(['error'=>'Invalid'],401);
  return ['token'=>$u->createToken('api')->plainTextToken];
 }
}
PHP

cat > routes/api.php <<'PHP'
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
Route::post('/v1/register',[AuthController::class,'register']);
Route::post('/v1/login',[AuthController::class,'login']);
PHP

cat > database/migrations/2026_01_01_000001_create_profiles.php <<'PHP'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration{
 public function up(){
  Schema::create('profiles',function(Blueprint $t){
   $t->id();
   $t->foreignId('user_id');
   $t->string('name');
   $t->boolean('is_kids')->default(false);
   $t->string('maturity_level');
   $t->timestamps();
  });
 }
 public function down(){Schema::dropIfExists('profiles');}
};
PHP

php artisan migrate

echo "=== Phase 1 Installed ==="
