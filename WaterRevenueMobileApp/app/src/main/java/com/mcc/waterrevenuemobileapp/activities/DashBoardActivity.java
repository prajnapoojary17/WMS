package com.mcc.waterrevenuemobileapp.activities;

import android.content.Intent;
import android.graphics.Color;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.Button;

import com.mcc.waterrevenuemobileapp.R;
import com.mcc.waterrevenuemobileapp.communicator.ServerCommunicator;
import com.mcc.waterrevenuemobileapp.utils.CommonUtils;


public class DashBoardActivity extends AppCompatActivity implements View.OnClickListener {
    private Button mBtnpulldata;
    private Button mBtngeneratebill;
    private Button mBtnscanprinter;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_dash_board);

        mBtnpulldata = findViewById(R.id.btnPulldata);
        mBtngeneratebill = findViewById(R.id.btnGenerateBill);
        mBtnscanprinter = findViewById(R.id.btnScanPrinter);

        findViewById(R.id.btnPulldata).setOnClickListener(this);
        findViewById(R.id.btnGenerateBill).setOnClickListener(this);
        findViewById(R.id.btnScanPrinter).setOnClickListener(this);
    }

    @Override
    public void onClick(View v) {
        switch (v.getId()) {
            case R.id.btnPulldata:
                if (CommonUtils.isNetworkAvailable(DashBoardActivity.this, mBtnpulldata, false)) {
                    ServerCommunicator serverCommunicator = new ServerCommunicator(DashBoardActivity.this);
                    serverCommunicator.pullData(DashBoardActivity.this, mBtnpulldata);

                } else {
                    CommonUtils.showSnackBar(mBtnpulldata, DashBoardActivity.this.getString(R.string.no_internet), Color.RED);
                }
                break;
            case R.id.btnGenerateBill:
                startActivity(new Intent(this,GenerateBillActivity.class));

                break;
            case R.id.btnScanPrinter:
                break;
            default:
                break;
        }
    }
}
