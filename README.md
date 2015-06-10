 # Cross Groups Filtering Plugin for ExpressionEngine 2.x

 This plugin lets you filter entries from different category groups.consider the example below:

        Suppose that you have such categories:

          Groups
            |
            |--CarTypesGroup
            |   |--SUV
            |   |--Sedan
            |   |--Truck
            |
            |--CarColorsGroup
                |--Red
                |--Blue
                |--Yellow

        There may be some cases that you want to filter entries like these:
            -all Red SUVs,
            -all Red or Blue, SUVs,
            -all Red,SUV or Trucks
            -all cars that are SUV or Sedan, and are, Red or Blue,

        In such cases this plugin comes to help!

## Usage

        Parameteres:
            "filters"
            the value set for filters should conform the format below:
            "group_name1:category_name11,category_name12,...,category_name1n;group_name2:category_name21,category_name22,...,category_name2n,..."

            Notice: there should be NOT be any spaces between commas and semi-colons or colons.however,a category and/or group name containing whitespaces
            is considered to be valid. e.g.: "car types".
            Notice: names should not contain ',' and/or ':' and/or ';'.

        Variables:
            "piped_entry_ids"
            after the entries are filtered,their IDs are concatenated by a '|' character and the result is accessible via this variable.

        A sample of usage:

            {exp:cross_groups_filtering filters="Car Type:SUV,Sedan;Car Colors:violet,red,dark blue"}
                {exp:channel:entries category_id="{piped_entry_ids}"}
                    Entry Title: {title}
                {/exp:channel:entries}
            {/exp:cross_groups_filtering}

## Changelog

    **1.0** *(2015-06-10)*

* Initial release