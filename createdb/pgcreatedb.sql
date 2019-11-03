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
  id serial NOT NULL,
  date integer NOT NULL,
  login integer,
  ipaddress integer,
  site text,
  sizeinbytes integer,
  httpstatus integer,
  par integer NOT NULL,
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
  id serial NOT NULL,
  date text NOT NULL,
  login text,
  ipaddress text,
  site text,
  sizeinbytes integer,
  method text,
  mime text,
  httpstatus text,
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
  id serial NOT NULL,
  date integer NOT NULL,
  login integer,
  ipaddress integer,
  httpstatus integer,
  site text,
  sizeinbytes integer,
  method text,
  mime text,
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
