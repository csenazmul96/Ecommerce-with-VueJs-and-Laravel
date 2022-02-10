<?php

namespace App\Http\Controllers\Admin;

use App\Enumeration\CouponType;
use App\Model\MetaSettings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use DB;

class SettingsController extends Controller
{
    public function color() 
    {
        $metaSettingsHeaderBGColor = MetaSettings::where('meta_key', 'header_color')->first();
        $metaSettingsHeaderFontColor = MetaSettings::where('meta_key', 'header_font_color')->first();
        $metaSettingsFooterBGColor = MetaSettings::where('meta_key', 'footer_color')->first();
        $metaSettingsFooterFontColor = MetaSettings::where('meta_key', 'footer_font_color')->first();
        
        return view('admin.dashboard.header_footer_color.index', compact('metaSettingsHeaderBGColor', 'metaSettingsHeaderFontColor', 'metaSettingsFooterBGColor', 'metaSettingsFooterFontColor'))->with('page_title', 'Header Footer Color');
    }

    public function postColor(Request $request)
    {
        $headerColor = ( $request->header_color == '' ) ? 'none' : $request->header_color ;
        $metaSettingsCounter = MetaSettings::where('meta_key', 'header_color')->count();
        if ( $metaSettingsCounter == 0 ) {
            $insertData = [
                'meta_key' => 'header_color',
                'meta_value' => $headerColor,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
            MetaSettings::insert($insertData);
        }
        else {
            $updateData = [
                'meta_value' => $headerColor
            ];
            MetaSettings::where('meta_key', 'header_color')->update($updateData);
        }
        
        $headerFontColor = ( $request->header_font_color == '' ) ? 'none' : $request->header_font_color ;
        $metaSettingsCounter = MetaSettings::where('meta_key', 'header_font_color')->count();
        if ( $metaSettingsCounter == 0 ) {
            $insertData = [
                'meta_key' => 'header_font_color',
                'meta_value' => $headerFontColor,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
            MetaSettings::insert($insertData);
        }
        else {
            $updateData = [
                'meta_value' => $headerFontColor
            ];
            MetaSettings::where('meta_key', 'header_font_color')->update($updateData);
        }
        
        $footerColor = ( $request->footer_color == '' ) ? 'none' : $request->footer_color ;
        $metaSettingsCounter = MetaSettings::where('meta_key', 'footer_color')->count();
        if ( $metaSettingsCounter == 0 ) {
            $insertData = [
                'meta_key' => 'footer_color',
                'meta_value' => $footerColor,
                'updated_at' => Carbon::now()
            ];
            MetaSettings::insert($insertData);
        }
        else {
            $updateData = [
                'meta_value' => $footerColor
            ];
            MetaSettings::where('meta_key', 'footer_color')->update($updateData);
        }
        
        $footerFontColor = ( $request->footer_font_color == '' ) ? 'none' : $request->footer_font_color ;
        $metaSettingsCounter = MetaSettings::where('meta_key', 'footer_font_color')->count();
        if ( $metaSettingsCounter == 0 ) {
            $insertData = [
                'meta_key' => 'footer_font_color',
                'meta_value' => $footerFontColor,
                'updated_at' => Carbon::now()
            ];
            MetaSettings::insert($insertData);
        }
        else {
            $updateData = [
                'meta_value' => $footerFontColor
            ];
            MetaSettings::where('meta_key', 'footer_font_color')->update($updateData);
        }

        return redirect()->back()->with('message', 'Information updated!');
    }
}
