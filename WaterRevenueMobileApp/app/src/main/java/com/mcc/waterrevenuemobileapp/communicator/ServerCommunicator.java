package com.mcc.waterrevenuemobileapp.communicator;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Context;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;

import com.mcc.waterrevenuemobileapp.R;
import com.mcc.waterrevenuemobileapp.activities.DashBoardActivity;

/**
 * class for api calls
 * In each method Volley library is used along with Gson library
 * in fromJson() method root class of the hierarchical api model class is passed.
 * Used http://www.jsonschema2pojo.org/ to generate model classes for api
 * Created by Shivakumar.k on 2/20/2018.
 */

public class ServerCommunicator {
    private static String TAG = ServerCommunicator.class.getSimpleName();
    private Context mActivity;
    private ProgressDialog mProgressDialog;

    public ServerCommunicator(Context mActivity) {
        this.mActivity = mActivity;
    }
    /**
     * hideProgressDialog
     */
    private void hideProgressDialog() {
        if (mProgressDialog.isShowing())
            mProgressDialog.dismiss();
    }
    /**
     * showProgressDialog
     */
    private void showProgressDialogWithMessage(String message, Context context) {

        if (!((Activity) mActivity).isFinishing()) {
            mProgressDialog = new ProgressDialog(mActivity, R.style.MyTheme);
            mProgressDialog.setIndeterminateDrawable(context.getResources().getDrawable(R.drawable.custom_progress_dialog));
            mProgressDialog.setCancelable(false);
            mProgressDialog.setMessage(message);
            mProgressDialog.setProgressStyle(android.R.style.Widget_ProgressBar_Small);
            mProgressDialog.show();
        }
    }
    /**
     * Check for login
     *
     * @param context
     * @param userName
     * @param password
     */
    public void checkLogin(final Context context, final View view, final String userName, final String password, final CheckBox checkBox) {

    }

    /**
     * Pull data from Server
     * @param dashBoardActivity
     * @param mBtnpulldata
     */
    public void pullData(DashBoardActivity dashBoardActivity, Button mBtnpulldata) {
    }
}