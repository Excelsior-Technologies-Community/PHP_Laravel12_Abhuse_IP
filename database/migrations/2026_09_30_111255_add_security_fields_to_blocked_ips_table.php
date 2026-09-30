<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blocked_ips', function (Blueprint $table) {
            $table->string('status')->default('active')->after('reason');
            $table->string('severity')->default('medium')->after('status');
            $table->unsignedTinyInteger('abuse_confidence_score')
                ->default(0)
                ->after('severity');
            $table->string('country')->nullable()->after('abuse_confidence_score');
            $table->string('isp')->nullable()->after('country');
            $table->timestamp('expires_at')->nullable()->after('isp');

            $table->index('status');
            $table->index('severity');
            $table->index('expires_at');
            $table->index('abuse_confidence_score');
        });
    }

    public function down(): void
    {
        Schema::table('blocked_ips', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['severity']);
            $table->dropIndex(['expires_at']);
            $table->dropIndex(['abuse_confidence_score']);

            $table->dropColumn([
                'status',
                'severity',
                'abuse_confidence_score',
                'country',
                'isp',
                'expires_at',
            ]);
        });
    }
};