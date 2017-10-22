/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  diego
 * Created: 30/09/2017
 */


/*Sale Daily Report /////////////////////////////////////////////////////////////*/
USE `lavaseco_db`;
DROP procedure IF EXISTS `saleDailyReport`;

DELIMITER $$
USE `lavaseco_db`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `saleDailyReport`(IN _from_date DATETIME,IN  _to_date DATETIME,IN  _sale_point_id int)
BEGIN
	IF _sale_point_id = 0 THEN
            select 
                ctotal.id,
                ctotal.salePoint,
                ctotal.total total,
                cpagado.total cancelado,
                ctotal.total - cpagado.total pendiente,
                ctotal.fecha 
            from
            (
                select 
                    s.id id,
                    s.name salePoint,
                    sum(ifnull(bd.quantity * bd.price, 0)) total,
                    date_format(b.created_at, '%d/%m/%Y') fecha
                from 
                    sale_point s
                left join bill b on s.id = b.sale_point_id
                left join bill_detail bd on b.id = bd.bill_id
                where 
                    b.created_at between _from_date and _to_date
                group by s.id, date_format(b.created_at, '%d/%m/%Y')
            ) ctotal,
            (
                select 
                    s2.id id,
                    s2.name salePoint,
                    sum(ifnull(pd.payment, 0)) total,
                    date_format(b2.created_at, '%d/%m/%Y') fecha
                from 
                    sale_point s2
                left join bill b2 on s2.id = b2.sale_point_id
                left join pay_detail pd on b2.id = pd.bill_id
                where 
                    b2.created_at between _from_date and _to_date
                group by s2.id, date_format(b2.created_at, '%d/%m/%Y')
            )cpagado
        where 
            cpagado.id = ctotal.id
        and cpagado.fecha = ctotal.fecha
        and cpagado.salePoint = ctotal.salePoint;
    ELSE
        select 
            ctotal.id,
            ctotal.salePoint,
            ctotal.total total,
            cpagado.total cancelado,
            ctotal.total - cpagado.total pendiente,
            ctotal.fecha 
        from
            (
                select 
                    s.id id,
                    s.name salePoint,
                    sum(ifnull(bd.quantity * bd.price, 0)) total,
                    date_format(b.created_at, '%d/%m/%Y') fecha
                from 
                    sale_point s
                left join bill b on s.id = b.sale_point_id
                left join bill_detail bd on b.id = bd.bill_id
                where 
                    s.id = _sale_point_id
                    and b.created_at between _from_date and _to_date
                group by s.id, date_format(b.created_at, '%d/%m/%Y')
            ) ctotal,
            (
                select 
                    s2.id id,
                    s2.name salePoint,
                    sum(ifnull(pd.payment, 0)) total,
                    date_format(b2.created_at, '%d/%m/%Y') fecha
                from 
                    sale_point s2
                left join bill b2 on s2.id = b2.sale_point_id
                left join pay_detail pd on b2.id = pd.bill_id
                where 
                    s2.id = _sale_point_id
                    and b2.created_at between _from_date and _to_date
                group by s2.id, date_format(b2.created_at, '%d/%m/%Y')
            )cpagado
        where 
            cpagado.id = ctotal.id
        and cpagado.fecha = ctotal.fecha
        and cpagado.salePoint = ctotal.salePoint;
    END IF;
END$$
DELIMITER ;

/*Sale Point Report sales/////////////////////////////////////////////////////////////*/
USE `lavaseco_db`;
DROP procedure IF EXISTS `salePointReport`;

DELIMITER $$
USE `lavaseco_db`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `salePointReport`(IN _from_date DATETIME,IN  _to_date DATETIME,IN  _sale_point_id int)
BEGIN
	IF _sale_point_id = 0 THEN
		select
			concat(b.sale_point_id,'-',b.id) id,
			bs.name estado,
			pa.name pago,
			sum(bd.quantity * bd.price) total,
			c.pagado cancelado,
			sum(bd.quantity * bd.price) - c.pagado pendiente,
			ifnull(cu.name, "No registro cliente")cliente,
			b.created_at fecha,
			concat(u.first_name, ' ',u.last_name) vendedor,
            s.name puntoVenta
		from 
			bill b
		inner join sale_point s on s.id = b.sale_point_id
		inner join bill_state bs on bs.id = b.bill_state_id
		inner join bill_detail bd on b.id = bd.bill_id
		inner join fos_user u on u.id = b.seller_user_id
		inner join payment_agreement pa on pa.id = b.payment_agreement_id
		left join customer cu on cu.id = b.customer_id
		inner join 
			(
				select b2.id id, sum(ifnull(pd.payment,0)) pagado
				from 
					bill b2
				left join pay_detail pd on b2.id = pd.bill_id
				group by b2.id
			) c on c.id = b.id
		where
			b.created_at between _from_date and _to_date
		group by b.id
        order by s.id, b.id asc;
	ELse
                    select
			concat(b.sale_point_id,'-',b.id) id,
			bs.name estado,
			pa.name pago,
			sum(bd.quantity * bd.price) total,
			c.pagado cancelado,
			sum(bd.quantity * bd.price) - c.pagado pendiente,
			ifnull(cu.name, "No registro cliente")cliente,
			b.created_at fecha,
			concat(u.first_name, ' ',u.last_name) vendedor,
            s.name puntoVenta
		from 
			bill b
		inner join sale_point s on s.id = b.sale_point_id
		inner join bill_state bs on bs.id = b.bill_state_id
		inner join bill_detail bd on b.id = bd.bill_id
		inner join fos_user u on u.id = b.seller_user_id
		inner join payment_agreement pa on pa.id = b.payment_agreement_id
		left join customer cu on cu.id = b.customer_id
		inner join 
			(
				select b2.id id, sum(ifnull(pd.payment,0)) pagado
				from 
					bill b2
				left join pay_detail pd on b2.id = pd.bill_id
				group by b2.id
			) c on c.id = b.id
		where 
			s.id = _sale_point_id and
			b.created_at between _from_date and _to_date
		group by b.id
        order by s.id, b.id asc;
	END IF;
END$$

DELIMITER ;


/*Sale Point Report sales/////////////////////////////////////////////////////////////*/
USE `lavaseco_db`;
DROP procedure IF EXISTS `cashTransaction`;

DELIMITER $$
USE `lavaseco_db`$$
CREATE PROCEDURE `cashTransaction` (IN _from_date DATETIME,IN  _to_date DATETIME,IN  _sale_point_id int)
BEGIN
    IF _sale_point_id = 0 THEN
        select
            sp.id,
            sp.name puntoVenta,
            ifnull(ti.ingresos, 0) ingresos,
            ifnull(te.egresos, 0) egresos,
            ifnull(ta.apertura, 0) apertura,
            ifnull(tc.cierre, 0) cierre
        from
            sale_point sp
        left join
            (	
                select 
                        sp.id id,
                        floor(avg(cta.payment)) apertura
                from 
                        sale_point sp
                inner join cash_transaction cta on sp.id = cta.sale_point_id
                        and cta.type_transaction_id = 1
                where cta.created_at between _from_date and _to_date
                group by sp.id, sp.name 
            ) ta on sp.id = ta.id
        left join
            (
                select 
                        sp.id id,
                        floor(avg(ctc.payment)) cierre
                from 
                        sale_point sp
                inner join cash_transaction ctc on sp.id = ctc.sale_point_id
                        and ctc.type_transaction_id = 2
                where ctc.created_at between _from_date and _to_date
                group by sp.id, sp.name
            ) tc on sp.id = tc.id
        left join 
            (
                select 
                        sp.id id,
                        sum(cti.payment) ingresos
                from 
                        sale_point sp
                inner join cash_transaction cti on sp.id = cti.sale_point_id
                        and cti.type_transaction_id = 3
                where cti.created_at between _from_date and _to_date
                group by sp.id, sp.name
            )ti  on sp.id = ti.id
        left join
            (
                select 
                        sp.id id,
                        sum(cte.payment) egresos
                from 
                        sale_point sp
                inner join cash_transaction cte on sp.id = cte.sale_point_id
                        and cte.type_transaction_id = 4
                where cte.created_at between _from_date and _to_date
                group by sp.id, sp.name
            ) te  on sp.id = te.id;
    ELSE
        select
            sp.id,
            sp.name puntoVenta,
            ifnull(ti.ingresos, 0) ingresos,
            ifnull(te.egresos, 0) egresos,
            ifnull(ta.apertura, 0) apertura,
            ifnull(tc.cierre, 0) cierre
        from
            sale_point sp
        left join
            (	
                select 
                        sp.id id,
                        floor(avg(cta.payment)) apertura
                from 
                        sale_point sp
                inner join cash_transaction cta on sp.id = cta.sale_point_id
                        and cta.type_transaction_id = 1
                where cta.created_at between _from_date and _to_date
                group by sp.id, sp.name 
            ) ta on sp.id = ta.id
        left join
            (
                select 
                        sp.id id,
                        floor(avg(ctc.payment)) cierre
                from 
                        sale_point sp
                inner join cash_transaction ctc on sp.id = ctc.sale_point_id
                        and ctc.type_transaction_id = 2
                where ctc.created_at between _from_date and _to_date
                group by sp.id, sp.name
            ) tc on sp.id = tc.id
        left join 
            (
                select 
                        sp.id id,
                        sum(cti.payment) ingresos
                from 
                        sale_point sp
                inner join cash_transaction cti on sp.id = cti.sale_point_id
                        and cti.type_transaction_id = 3
                where cti.created_at between _from_date and _to_date
                group by sp.id, sp.name
            )ti  on sp.id = ti.id
        left join
            (
                select 
                        sp.id id,
                        sum(cte.payment) egresos
                from 
                        sale_point sp
                inner join cash_transaction cte on sp.id = cte.sale_point_id
                        and cte.type_transaction_id = 4
                where cte.created_at between _from_date and _to_date
                group by sp.id, sp.name
            ) te  on sp.id = te.id
        where sp.id = _sale_point_id;
    END IF;
END$$

DELIMITER ;