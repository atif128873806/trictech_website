<?php

return [
    /*
     | Mailboxes that receive a notification for every website inquiry.
     |
     | INQUIRY_RECIPIENT accepts a comma separated list, so adding an owner or a
     | shared operations mailbox is a .env edit rather than a code change:
     |
     |   INQUIRY_RECIPIENT="info@tricetech.ca,junaid@tricetech.ca,coo@tricetech.ca"
     |
     | Every listed address receives its own copy, and each reply goes straight
     | back to the visitor because the notification sets a reply-to.
     */
    'inquiry_recipients' => value(function () {
        $configured = (string) env('INQUIRY_RECIPIENT', 'info@tricetech.ca,junaid@tricetech.ca,coo@tricetech.ca');

        return array_values(array_filter(array_map(
            static fn (string $address): string => mb_strtolower(trim($address)),
            preg_split('/[,;\s]+/', $configured) ?: [],
        )));
    }),
];
