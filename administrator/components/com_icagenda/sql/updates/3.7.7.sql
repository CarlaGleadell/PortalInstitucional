UPDATE `#__icagenda` SET version='3.7.7', releasedate='2019-01-09' WHERE id=3;

--
-- Indexes for table `#__icagenda_category`
--
ALTER TABLE `#__icagenda_category`
  ADD KEY `idx_state` (`state`),
  ADD KEY `idx_language` (`language`);

--
-- Indexes for table `#__icagenda_customfields`
--
ALTER TABLE `#__icagenda_customfields`
  ADD KEY `idx_state` (`state`),
  ADD KEY `idx_parent_form` (`parent_form`),
  ADD KEY `idx_language` (`language`);

--
-- Indexes for table `#__icagenda_customfields_data`
--
ALTER TABLE `#__icagenda_customfields_data`
  ADD KEY `idx_state` (`state`),
  ADD KEY `idx_parent_form` (`parent_form`),
  ADD KEY `idx_parent_id` (`parent_id`),
  ADD KEY `idx_language` (`language`);

--
-- Indexes for table `#__icagenda_events`
--
ALTER TABLE `#__icagenda_events`
  ADD KEY `idx_state` (`state`),
  ADD KEY `idx_approval` (`approval`),
  ADD KEY `idx_language` (`language`),
  ADD KEY `idx_catid` (`catid`);

--
-- Indexes for table `#__icagenda_feature`
--
ALTER TABLE `#__icagenda_feature`
  ADD KEY `idx_state` (`state`);

--
-- Indexes for table `#__icagenda_feature_xref`
--
ALTER TABLE `#__icagenda_feature_xref`
  ADD KEY `idx_event_id` (`event_id`),
  ADD KEY `idx_feature_id` (`feature_id`);

--
-- Indexes for table `#__icagenda_filters`
--
ALTER TABLE `#__icagenda_filters`
  ADD KEY `idx_state` (`state`);

--
-- Indexes for table `#__icagenda_registration`
--
ALTER TABLE `#__icagenda_registration`
  ADD KEY `idx_state` (`state`),
  ADD KEY `idx_userid` (`userid`),
  ADD KEY `idx_eventid` (`eventid`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_by` (`created_by`);

--
-- Indexes for table `#__icagenda_user_actions`
--
ALTER TABLE `#__icagenda_user_actions`
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_parent_form` (`parent_form`),
  ADD KEY `idx_parent_id` (`parent_id`),
  ADD KEY `idx_state` (`state`);
