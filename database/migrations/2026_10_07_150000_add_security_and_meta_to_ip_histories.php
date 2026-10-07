<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ip_histories', function (Blueprint $table) {
            $table->string('isp')->nullable()->after('longitude');
            $table->string('asn')->nullable()->after('isp');
            $table->string('timezone')->nullable()->after('asn');
            $table->string('risk_score', 20)->default('Safe')->after('timezone');
            $table->boolean('is_vpn_proxy')->default(false)->after('risk_score');
            $table->boolean('is_blacklisted')->default(false)->after('is_vpn_proxy');
        });
    }

    public function down(): void
    {
        Schema::table('ip_histories', function (Blueprint $table) {
            $table->dropColumn([
                'isp',
                'asn',
                'timezone',
                'risk_score',
                'is_vpn_proxy',
                'is_blacklisted'
            ]);
        });
    }
};
