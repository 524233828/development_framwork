# 慢查询整理

## yi_wx_log表

SELECT `id`,`times` FROM `yi_wx_log` WHERE `from_user` = 'on7Kxwg005hBPI0oZxWvdI_LPof0' LIMIT 1


## yi_user_order表
SELECT COUNT(*) FROM `yi_user_order` WHERE `uid` = '151938840700200090' AND `status` IN (1,2) AND `type` = 1

## yi_user_ask表
SELECT COUNT(*) FROM `yi_user_ask` INNER JOIN `yi_user` ON `yi_user_ask`.`answer_uid` = `yi_user`.`id` WHERE `yi_user`.`status` = 1 AND `yi_user_ask`.`status` = 2 AND `is_public` = 1 AND `ask_type` IN (0,1) AND `order_id` != ''

SELECT `yi_user_ask`.`id` AS `ask_id`,`ask`,`media_time`,`touting`,`answer_uid`,`mess_id` FROM `yi_user_ask` INNER JOIN `yi_user` ON `yi_user_ask`.`answer_uid` = `yi_user`.`id` WHERE `yi_user`.`status` = 1 AND `yi_user_ask`.`status` = 2 AND `is_public` = 1 AND `ask_type` IN (0,1) AND `order_id` != '' ORDER BY `sort` DESC LIMIT 0,10

SELECT * FROM `yi_user_ask` WHERE `answer_uid` = 1506614684 AND `status` = 1

## yi_user_mess

SELECT GROUP_CONCAT(yi_user_mess.id) as `count` FROM yi_user_mess LEFT JOIN `yi_user_ask` ON yi_user_mess.id=yi_user_ask.mess_id WHERE user_id='1512360691' and yi_user_mess.`status`=0 and yi_user_ask.id IS NULL

SELECT count(yi_user_mess.id) as `count` FROM yi_user_mess LEFT JOIN `yi_user_ask` ON yi_user_mess.id=yi_user_ask.mess_id WHERE user_id='151601968400000030' and yi_user_mess.`status`=0 and yi_user_ask.id IS NULL


## yi_url_assign_log

SELECT `id` FROM `yi_url_assign_log` WHERE ( `create_time` >= 1519315200 AND `create_time` <= 1519401599 ) AND `pid` = 67 LIMIT 1


## yi_user

SELECT count(id) sum,`channel`,`classification` FROM `yi_user` WHERE `bind_time` BETWEEN 1519228800 AND 1519315199 GROUP BY channel,classification