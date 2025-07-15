package com.soapclient;

import com.example.soapclient.UserManagementApp;

import javax.swing.*;

public class Main {
    public static void main(String[] args) {
        SwingUtilities.invokeLater(new Runnable() {
            @Override
            public void run() {
                UserManagementApp app = new UserManagementApp();
            }
        });
    }
}