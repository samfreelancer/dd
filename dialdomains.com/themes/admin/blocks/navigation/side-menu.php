<div class="page-sidebar nav-collapse collapse">

  <ul class="page-sidebar-menu">
    <li>
      <div class="sidebar-toggler hidden-phone"></div>
    </li>
    <li>
      <div class="hidden-phone"><br></div>
    </li>
    <li class="start <?php echo adfMainNavigation::isCurrent('public', 'active');?>">
      <a href="/index">
        <i class="icon-home"></i>
        <span class="title">Dashboard</span>
        <span class="selected"></span>
      </a>
    </li>
    <li class="<?php echo adfMainNavigation::isCurrent('manage-users', 'active');?>">
      <a href="/manage-users/default/index">
        <i class="icon-user"></i>
        <span class="title">Users</span>
      </a>
    </li>
    <li class="<?php echo adfMainNavigation::isCurrent('manage-permissions', 'active');?>">
      <a href="javascript:;">
        <i class="icon-bookmark-empty"></i>
        <span class="title">Permissions</span>
        <span class="arrow "></span>
      </a>
      <ul class="sub-menu">
        <li >
          <a href="/manage-permissions/permissions/index">
            Permissions</a>
        </li>
        <li >
          <a href="/manage-permissions/groups/index">
            Groups</a>
        </li>
      </ul>
    </li>
  </ul>

</div>
