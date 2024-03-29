<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateWkMorphLinkTable extends Migration
{
    public function up()
    {
        Schema::create(config('wk-core.table.morph-link.links'), function (Blueprint $table) {
            $table->uuid('id');
            $table->uuidMorphs('morph');
            $table->string('type')->nullable();
            $table->string('category')->nullable();
            $table->string('serial')->nullable();
            $table->string('target', 10)->default('_blank');
            $table->string('url');
            $table->string('extension')->nullable();
            $table->unsignedBigInteger('order')->nullable();
            $table->boolean('is_enabled')->default(0);
            $table->unsignedBigInteger('count_access')->nullable();
            $table->unsignedBigInteger('count_download')->nullable();

            $table->timestampsTz();
            $table->softDeletes();

            $table->primary('id');
            $table->index(['morph_type', 'morph_id', 'type']);
            $table->index(['type', 'category']);
            $table->index('serial');
            $table->index('url');
            $table->index('is_enabled');
        });
        if (!config('wk-morph-link.onoff.core-lang_core')) {
            Schema::create(config('wk-core.table.morph-link.links_lang'), function (Blueprint $table) {
                $table->uuid('id');
                $table->uuidMorphs('morph');
                $table->uuid('user_id')->nullable();
                $table->string('code');
                $table->string('key');
                $table->text('value')->nullable();
                $table->boolean('is_current')->default(1);

                $table->timestampsTz();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')
                    ->on(config('wk-core.table.user'))
                    ->onDelete('set null')
                    ->onUpdate('cascade');

                $table->primary('id');
            });
        }
    }

    public function down() {
        Schema::dropIfExists(config('wk-core.table.morph-link.links_lang'));
        Schema::dropIfExists(config('wk-core.table.morph-link.links'));
    }
}
