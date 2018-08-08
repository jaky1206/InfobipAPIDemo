-- Create Database
-- CREATE DATABASE `infobip_api_demo`;

USE infobip_api_demo;

-- Table structure for table `messages`
CREATE TABLE `messages` (
  `bulk_id` varchar(36) DEFAULT NULL COMMENT 'The ID which uniquely identifies the request. Bulk ID will be received only when you send a message to more than one destination address.',
  `from` varchar(15) DEFAULT NULL COMMENT 'Represents a sender ID which can be alphanumeric or numeric. Alphanumeric sender ID length should be between 3 and 11 characters (Example: CompanyName). Numeric sender ID length should be between 3 and 14 characters.',
  `to` varchar(15) NOT NULL COMMENT 'Message destination address. Addresses must be in international format (Example: 41793026727).',
  `message_id` varchar(36) NOT NULL COMMENT 'The ID that uniquely identifies the message sent.',
  `text` varchar(4000) DEFAULT NULL COMMENT 'Text of the message that will be sent.',
  `flash` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Can be true or false. If the value is set to true, a flash SMS will be sent. Otherwise, a normal SMS will be sent. The default value is false.',
  `transliteration` varchar(36) DEFAULT NULL COMMENT 'Conversion of a message text from one script to another. Possible values: "TURKISH", "GREEK", "CYRILLIC", "CENTRAL_EUROPEAN" and "NON_UNICODE".',
  `language_code` varchar(4) DEFAULT NULL COMMENT 'Code for language character set of a message text. Possible values: TR for Turkish, ES for Spanish and PT for Portuguese.',
  `intermediate_report` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'The real-time Intermediate delivery report that will be sent on your callback server. Can be true or false.',
  `notifyUrl` varchar(250) NOT NULL DEFAULT COMMENT 'The URL on your callback server on which the Delivery report will be sent.',
  `notify_content_type` varchar(36) NOT NULL DEFAULT 'application/json' COMMENT 'Preferred Delivery report content type. Can be application/json or application/xml.',
  `callback_data` varchar(36) NOT NULL DEFAULT 'DLR callback data' COMMENT 'Additional clients data that will be sent on the notifyUrl. The maximum value is 200 characters.',
  `validity_period` int(11) NOT NULL DEFAULT '1440' COMMENT 'The message validity period in minutes. When the period expires, it will not be allowed for the message to be sent. Validity period longer than 48h is not supported (in this case, it will be automatically set to 48h).',
  `send_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Date and time when the message is to be sent. Used for scheduled SMS (SMS not sent immediately, but at scheduled time).',
  `is_delivery_time_applicable` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'time window applicable or not',
  `delivery_time_window_from_hour` int(11) DEFAULT NULL COMMENT 'time windows in which the message can be sent',
  `delivery_time_window_from_minute` int(11) DEFAULT NULL COMMENT 'time windows in which the message can be sent',
  `delivery_time_window_to_hour` int(11) DEFAULT NULL COMMENT 'time windows in which the message can be sent',
  `delivery_time_window_to_minute` int(11) DEFAULT NULL COMMENT 'time windows in which the message can be sent',
  `delivery_time_window_days` varchar(80) DEFAULT NULL COMMENT 'time windows in which the message can be sent',
  `is_tracking_applicable` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'tracking applicable or not',
  `tracking_track` varchar(10) DEFAULT NULL COMMENT 'Indicates if the message has to be tracked for Conversion rates. Possible values: SMS and URL',
  `tracking_type` varchar(36) DEFAULT NULL COMMENT 'User defined type of the Conversion tracking process or flow type or message type, etc. Example: ONE_TIME_PIN or SOCIAL_INVITES.',
  `tracking_base_url` varchar(250) DEFAULT NULL COMMENT 'Custom base url used for shortening links from SMS text in URL Conversion rate tracking use-case.',
  `process_key` varchar(36) DEFAULT NULL COMMENT 'Key that uniquely identifies Conversion tracking process.',
  `organization_id` varchar(20) NOT NULL,
  `schedule_id` varchar(500) DEFAULT NULL,
  `instatus` varchar(200) DEFAULT NULL,
  `outstatus` varchar(200) DEFAULT NULL,
  `delivery_status` varchar(400) NOT NULL,
  `service` varchar(200) DEFAULT NULL,
  `intime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `outtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `processtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `correlator` varchar(200) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `sms_count` int(11) NOT NULL DEFAULT '1',
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
