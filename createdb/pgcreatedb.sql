-- --------------------------------------------------------

--
-- Table structure for table `scsq_alias`
--

CREATE TABLE IF NOT EXISTS scsq_alias
(
  id serial NOT NULL,
  name text,
  typeid integer,
  userlogin text,
  password text,
  hash text,
  active integer,
  tableid integer,
  CONSTRAINT scsq_alias_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE scsq_alias
  OWNER TO postgres;

-- --------------------------------------------------------

--
-- Table structure for table `scsq_aliasingroups`
--

CREATE TABLE IF NOT EXISTS scsq_aliasingroups
(
  id serial NOT NULL,
  groupid integer NOT NULL,
  aliasid integer NOT NULL,
  CONSTRAINT scsq_aliasingroups_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE scsq_aliasingroups
  OWNER TO postgres;

-- --------------------------------------------------------

--
-- Table structure for table `scsq_groups`
--

CREATE TABLE IF NOT EXISTS scsq_groups
(
  id serial NOT NULL,
  name text NOT NULL,
  typeid integer NOT NULL,
  comment text,
  userlogin text,
  password text,
  hash text,
  active integer DEFAULT 0,
  CONSTRAINT scsq_groups_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE scsq_groups
  OWNER TO postgres;


-- --------------------------------------------------------

--
-- Table structure for table `scsq_httpstatus`
--

CREATE TABLE IF NOT EXISTS scsq_httpstatus
(
  id serial NOT NULL,
  name text NOT NULL,
  CONSTRAINT scsq_httpstatus_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE scsq_httpstatus
  OWNER TO postgres;

-- --------------------------------------------------------

--
-- Table structure for table `scsq_ipaddress`
--

CREATE TABLE IF NOT EXISTS scsq_ipaddress
(
  id serial NOT NULL,
  name text NOT NULL,
  CONSTRAINT scsq_ipaddress_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE scsq_ipaddress
  OWNER TO postgres;

-- --------------------------------------------------------

--
-- Table structure for table `scsq_logins`
--

CREATE TABLE IF NOT EXISTS scsq_logins
(
  id serial NOT NULL,
  name text NOT NULL,
  CONSTRAINT scsq_logins_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE scsq_logins
  OWNER TO postgres;

-- --------------------------------------------------------

--
-- Table structure for table `scsq_logtable`
--

CREATE TABLE IF NOT EXISTS scsq_logtable
(
  id serial NOT NULL,
  datestart integer,
  dateend integer,
  message text,
  CONSTRAINT scsq_logtable_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE scsq_logtable
  OWNER TO postgres;

-- --------------------------------------------------------

--
-- Table structure for table `scsq_quicktraffic`
--

CREATE TABLE IF NOT EXISTS scsq_quicktraffic
(
  id bigserial NOT NULL,
  date integer NOT NULL,
  login integer,
  ipaddress integer,
  site text,
  sizeinbytes bigint,
  httpstatus integer,
  par integer NOT NULL,
  numproxy integer,
  CONSTRAINT scsq_quicktraffic_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE scsq_quicktraffic
  OWNER TO postgres;

CREATE INDEX scsq_quicktraffic_date_idx
  ON scsq_quicktraffic
  USING btree
  (date);

-- --------------------------------------------------------

--
-- Table structure for table `scsq_temptraffic`
--

CREATE TABLE IF NOT EXISTS scsq_temptraffic
(
  id bigserial NOT NULL,
  date text NOT NULL,
  login text,
  ipaddress text,
  site text,
  sizeinbytes bigint,
  method text,
  mime text,
  httpstatus text,
  numproxy integer,
  CONSTRAINT scsq_temptraffic_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE scsq_temptraffic
  OWNER TO postgres;
  
-- --------------------------------------------------------

--
-- Table structure for table `scsq_traffic`
--

CREATE TABLE IF NOT EXISTS scsq_traffic
(
  id bigserial NOT NULL,
  date integer NOT NULL,
  login integer,
  ipaddress integer,
  httpstatus integer,
  site text,
  sizeinbytes bigint,
  method text,
  mime text,
  numproxy integer,
  CONSTRAINT scsq_traffic_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE scsq_traffic
  OWNER TO postgres;



CREATE INDEX scsq_traffic_date_idx
  ON scsq_traffic
  USING btree
  (date);

-- --------------------------------------------------------
--
-- Table structure for table `scsq_modules`
--

CREATE TABLE IF NOT EXISTS scsq_modules
(
  id serial NOT NULL,
  name text NOT NULL,
  link text NOT NULL,
  CONSTRAINT scsq_modules_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE scsq_modules
  OWNER TO postgres;

-- --------------------------------------------------------



-- --------------------------------------------------------
--
-- Table: scsq_sqper_activerequests


CREATE TABLE IF NOT EXISTS scsq_sqper_activerequests
(
  id serial NOT NULL,
  date integer,
  username text,
  ipaddress text NOT NULL,
  site text NOT NULL,
  sizeinbytes integer NOT NULL,
  seconds integer NOT NULL,
  CONSTRAINT scsq_sqper_activerequests_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE scsq_sqper_activerequests
  OWNER TO postgres;
  
  
  -- --------------------------------------------------------

--
-- Table structure for table scsq_sqper_trend10
--

CREATE TABLE IF NOT EXISTS scsq_sqper_trend10 (
  id serial  NOT NULL,
  date integer NOT NULL,
  value integer NOT NULL,
  par integer DEFAULT NULL,
  CONSTRAINT scsq_sqper_trend10_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE scsq_sqper_trend10
  OWNER TO postgres;

--
-- Table structure for table scsq_modules_param
--

CREATE TABLE IF NOT EXISTS scsq_modules_param (
    id serial NOT NULL,
    module text,
    param text,
    val text,
    switch integer,
    comment text,
    CONSTRAINT scsq_modules_param_pkey PRIMARY KEY (id)
);

ALTER TABLE scsq_modules_param
  OWNER TO postgres;

INSERT INTO scsq_modules_param (id, module, param, val, switch, comment) VALUES
(1, 'Cache', 'enabled', '', 1, 'Enable module'),
(2, 'Global', 'language', 'en', 0, 'Global language'),
(3, 'Global', 'useLoginalias', '', 1, 'Use login alias'),
(4, 'Global', 'useIpaddressalias', 'on', 1, 'Use ip address alias'),
(5, 'Global', 'enableUseiconv', '', 1, 'Use iconv'),
(6, 'Global', 'enableShowDayNameInReports', 'on', 1, 'Show day name in reports'),
(7, 'Global', 'enableTrafficObjectsInStat', '', 1, 'Show traffic objects in stat'),
(8, 'Global', 'refreshPeriod', '5', 0, 'Seconds to autorefresh online report'),
(9, 'Global', 'bandwidth', '10', 0, 'Bandwidth for online reports in MBits'),
(10, 'Global', 'graphtype_trafficbyhours', '0', 0, 'type graph for traffic by hours 0 -line, 1 - histogram'),
(11, 'Global', 'roundTrafficDigit', '-1', 0, 'How many digits to round traffic. If -1 = no round'),
(12, 'Global', 'countTopSitesLimit', '10', 0, 'Limit of top report Traffic Sites'),
(13, 'Global', 'countTopLoginLimit', '10', 0, 'Limit of top report Traffic Logins'),
(14, 'Global', 'countTopIpLimit', '10', 0, 'Limit of top report Traffic Ipaddress'),
(15, 'Global', 'countPopularSitesLimit', '10', 0, 'Limit of top report Popular sites'),
(16, 'Global', 'countWhoDownloadBigFilesLimit', '10', 0, 'Limit of top report WhoDownloadBigFiles'),
(17, 'Global', 'enableNofriends', '', 1, 'Enable hide friends in reports'),
(18, 'Global', 'goodLogins', '', 0, 'Friends list, separate with blank. For example, $goodLogins="Vasya Sergey Petr"'),
(19, 'Global', 'goodIpaddress', '', 0, 'Friends list, separate with blank. For example, $goodIpaddress="172.16.1.1 172.16.5.16"'),
(20, 'Global', 'enableNoSites', '', 1, 'Enable filter good sites. If enable, $goodSites were not shown in statistic.'),
(21, 'Global', 'goodSites', '', 0, 'List good sites $goodSites="vk.me facebook.com ipp"'),
(22, 'Global', 'csv_decimalSymbol', ',', 0, 'decimal symbol separator for CSV export'),
(23, 'Global', 'globaltheme', 'default', 0, 'theme'),
(24, 'Global', 'enableUseDecode', '', 1, 'use urldecode to decode % characters in request'),
(25, 'Global', 'workingHours', '8-00:12-30:13-00:17-20', 0, 'Set working hours. For example, set two periods From 8:00 to 12:30 and from 13 to 17'),
(26, 'Global', 'showZeroTrafficInReports', '0', 0, 'Show zero traffic in reports'),
(27, 'Global', 'enableFilterSites', '', 1, 'Enable use filter sites. If enable, only filterSites were shown in statistic.'),
(28, 'Global', 'filterSites', '', 0, 'List filter sites "vk.me facebook.com:433 ipp" without quotation.'),
(29, 'Global', 'DefaultRepDate', 'on', 1, 'After start, first report will be opened on yesterday(on) or current day (off).'),
(30, 'Global', 'enableFilterSizeinbytes', '', 1, 'Enable filter size in bytes for report by day time'),
(31, 'Global', 'filterSizeinbytes', '', 0, 'Set sizeinbytes condition for example ">1000" means greater than 1000 bytes. "<1000" means less than 1000 bytes or "between 100 and 200"');

CREATE OR REPLACE FUNCTION crc32(text_string text) RETURNS bigint AS $$
DECLARE
    tmp bigint;
    i int;
    j int;
    byte_length int;
    binary_string bytea;
BEGIN
    IF text_string IS NULL OR text_string = '' THEN
        RETURN 0;
    END IF;

    i = 0;
    tmp = 4294967295;
    byte_length = bit_length(text_string) / 8;
    binary_string = decode(replace(text_string, E'\\\\', E'\\\\\\\\'), 'escape');
    LOOP
        tmp = (tmp # get_byte(binary_string, i))::bigint;
        i = i + 1;
        j = 0;
        LOOP
            tmp = ((tmp >> 1) # (3988292384 * (tmp & 1)))::bigint;
            j = j + 1;
            IF j >= 8 THEN
                EXIT;
            END IF;
        END LOOP;
        IF i >= byte_length THEN
            EXIT;
        END IF;
    END LOOP;
    RETURN (tmp # 4294967295);
END
$$ IMMUTABLE LANGUAGE plpgsql;