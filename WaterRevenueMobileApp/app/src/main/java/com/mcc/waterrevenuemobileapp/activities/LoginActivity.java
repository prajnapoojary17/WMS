package com.mcc.waterrevenuemobileapp.activities;

import android.content.Intent;
import android.content.res.Configuration;
import android.graphics.Color;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.text.TextUtils;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.ProgressBar;

import com.mcc.waterrevenuemobileapp.R;
import com.mcc.waterrevenuemobileapp.communicator.ServerCommunicator;
import com.mcc.waterrevenuemobileapp.constants.AppConstants;
import com.mcc.waterrevenuemobileapp.utils.CommonUtils;

public class LoginActivity extends AppCompatActivity implements View.OnClickListener {
    private Button loginButton;
    private EditText etUserName, etPassword;
    private ProgressBar progressBar;
    @Override
    public void onConfigurationChanged(Configuration newConfig) {
        super.onConfigurationChanged(newConfig);
        CommonUtils.setOrientation(LoginActivity.this);
    }
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);
        CommonUtils.setOrientation(LoginActivity.this);
        initUI();
    }



    @Override
    public void onClick(View view) {
        switch (view.getId()){
            case R.id.loginButton:
                doLogin(view, etUserName, etPassword);
                startActivity(new Intent(LoginActivity.this,DashBoardActivity.class));
            break;
            default:
        }
    }

    /**
     * Initilizes UI
     */
    private void initUI() {
        etUserName = (EditText) findViewById(R.id.editText);
        etPassword = (EditText) findViewById(R.id.editText2);
        findViewById(R.id.loginButton).setOnClickListener(this);

        if (CommonUtils.getBoolean(LoginActivity.this, AppConstants.SAVE_LOGIN)) {
            etUserName.setText(CommonUtils.getString(LoginActivity.this, AppConstants.SAVEUSER_NAME));
            etPassword.setText(CommonUtils.getString(LoginActivity.this, AppConstants.SAVE_PASSWORD));
            ((CheckBox) findViewById(R.id.remberMeCheck)).setChecked(true);
        } else {
            ((CheckBox) findViewById(R.id.remberMeCheck)).setChecked(false);
            etUserName.setText("");
            etPassword.setText("");
        }
    }
    /**
     * Login method with validation
     */

    public void doLogin(View view, EditText userName, EditText password) {
        if (validateRequest(userName, password)) {
            if (CommonUtils.isNetworkAvailable(LoginActivity.this, view, false)) {
                ServerCommunicator serverCommunicator = new ServerCommunicator(LoginActivity.this);
                serverCommunicator.checkLogin(LoginActivity.this, view, userName.getText().toString().trim(), password.getText().toString().trim(), ((CheckBox) findViewById(R.id.remberMeCheck)));

            } else {

                CommonUtils.showSnackBar(view,LoginActivity.this.getString(R.string.no_internet), Color.RED);
            }
        }
    }

    /**
     * Request validator
     * @param userName
     * @param password
     * @return
     */
    private boolean validateRequest(EditText userName, EditText password) {
        boolean isValid = false;
        // Check for empty data in the form
        if (TextUtils.isEmpty(userName.getText().toString().trim())) {
            userName.requestFocus();
            userName.setError("Please enter Username");
        } else if (TextUtils.isEmpty(password.getText().toString().trim())) {
            password.requestFocus();
            password.setError("Please enter Password");
        } else {
            isValid = true;
        }
        return isValid;
    }
}
