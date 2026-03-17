use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('piar_adjustments', function (Blueprint $table) {

            $table->unsignedBigInteger('teacher_id')->after('period');

            $table->foreign('teacher_id')
                  ->references('id')
                  ->on('users_teachers')
                  ->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::table('piar_adjustments', function (Blueprint $table) {

            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');

        });
    }
};