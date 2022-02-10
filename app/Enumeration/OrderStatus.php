<?php
    namespace App\Enumeration;

    class OrderStatus {
        public static $INIT = 1;
        public static $NEW_ORDER = 2;
        public static $CONFIRM_ORDER = 3;
        public static $PARTIALLY_SHIPPED_ORDER = 4;
        public static $FULLY_SHIPPED_ORDER = 5;
        public static $BACK_ORDER = 6;
        public static $CANCEL_BY_BUYER = 7;
        public static $CANCEL_BY_VENDOR = 8;
        public static $CANCEL_BY_AGREEMENT = 9;
        public static $RETURNED = 10;
        public static $DECLINE = 11;
    }