package com.mcc.waterrevenuemobileapp.utils;

import android.app.Activity;
import android.content.Context;
import android.content.SharedPreferences;
import android.content.pm.ActivityInfo;
import android.graphics.Color;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.preference.PreferenceManager;
import android.support.design.widget.Snackbar;
import android.view.View;
import android.widget.TextView;

import com.mcc.waterrevenuemobileapp.R;

/**
 * Created by Shivakumar.k on 2/16/2018.
 */

public class CommonUtils {
    /**
     * snack bar with message param and color
     * @param view
     * @param message
     * @param colorID
     */
    public static void showSnackBar(View view, String message, int colorID){
        Snackbar snackbar = Snackbar
                .make(view, message, Snackbar.LENGTH_SHORT);

        View sbView = snackbar.getView();
        TextView textView = (TextView) sbView.findViewById(android.support.design.R.id.snackbar_text);
        textView.setTextColor(colorID);
        snackbar.show();
    }

    /**
     * Store Boolean preference
     * @param context
     * @param value
     * @param KEY
     */
    public static void storeBoolean(Context context, boolean value, String KEY) {
        final SharedPreferences prefs = PreferenceManager
                .getDefaultSharedPreferences(context);
        SharedPreferences.Editor editor = prefs.edit();
        editor.putBoolean(KEY, value);
        editor.commit();
    }

    /**
     * Retrieve stored boolean preference
     * @param context
     * @param KEY
     * @return
     */
    public static boolean getBoolean(Context context, String KEY)
    {
        final SharedPreferences prefs =  PreferenceManager
                .getDefaultSharedPreferences(context);
        boolean value = prefs.getBoolean(KEY,false);

        return value;
    }

    /**
     * Retrieve stored Integer preference
     * @param context
     * @param KEY
     * @return
     */
    public static int getInt(Context context, String KEY) {
        final SharedPreferences prefs =  PreferenceManager
                .getDefaultSharedPreferences(context);
        int value = prefs.getInt(KEY,0);

        return value;
    }

    /**
     * Store Integer preference
     * @param context
     * @param value
     * @param KEY
     */
    public static void storeInt(Context context, int value, String KEY) {
        final SharedPreferences prefs =  PreferenceManager
                .getDefaultSharedPreferences(context);
        SharedPreferences.Editor editor = prefs.edit();
        editor.putInt(KEY ,value);
        editor.commit();
    }
    /**
     * Forcing the orientation only portrait for mobile and both orientation for tab
     *
     * @param context context
     */
    public static void setOrientation(Activity context) {
            context.setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_PORTRAIT);
    }


    /**
     * Retrieve stored String preference
     * @param context
     * @param KEY
     * @return
     */
    public static String getString(Context context, String KEY) {
        final SharedPreferences prefs =  PreferenceManager
                .getDefaultSharedPreferences(context);
        String value = prefs.getString(KEY,null);

        return value;
    }

    /**
     * Store String preference
     * @param context
     * @param value
     * @param KEY
     */
    public static void storeString(Context context,String value,String KEY) {

        final SharedPreferences prefs =  PreferenceManager
                .getDefaultSharedPreferences(context);
        SharedPreferences.Editor editor = prefs.edit();
        editor.putString(KEY ,value);
        editor.commit();
    }

    /**
     * To check Network available or not
     *
     * @param context     - Context
     * @param view        - View
     * @param alertDialog - boolean
     * @return boolean
     */
    public static boolean isNetworkAvailable(Context context, View view, boolean alertDialog) {
        ConnectivityManager connectivityManager = (ConnectivityManager) context.getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo networkInfo = connectivityManager.getActiveNetworkInfo();
        if (networkInfo != null && networkInfo.isConnected()) {
            return true;
        } else {
            if (alertDialog) {
                showSnackBar(view,context.getString(R.string.no_internet),Color.RED);
            }

        }
        return false;
    }
    /**
     * To show snack bar for Api failure
     *
     * @param view - View
     */
    public static void showSnackBarApiFailure(View view) {
        if (view == null) {
            return;
        }

        Snackbar snackbar = Snackbar
                .make(view, R.string.error_occured, Snackbar.LENGTH_SHORT);
        View sbView = snackbar.getView();
        TextView textView = (TextView) sbView.findViewById(android.support.design.R.id.snackbar_text);
        textView.setTextColor(Color.YELLOW);
        snackbar.show();
    }

    public  static void snackBarWithOK(View view, final Activity context){
        Snackbar snackbar = Snackbar
                .make(view, R.string.no_internet, Snackbar.LENGTH_LONG)
                .setAction("OK", new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                        context.finish();
                    }
                });

// Changing message text color
        snackbar.setActionTextColor(Color.RED);

// Changing action button text color
        View sbView = snackbar.getView();
        TextView textView = (TextView) sbView.findViewById(android.support.design.R.id.snackbar_text);
        textView.setTextColor(Color.YELLOW);
        snackbar.setDuration(Snackbar.LENGTH_INDEFINITE);
        snackbar.show();
    }
}
