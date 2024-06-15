<!-- Body Wrapper -->
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
 data-sidebar-position="fixed" data-header-position="fixed">

 <!-- Sidebar Start -->
 <aside class="left-sidebar">
   <!-- Sidebar scroll -->
   <div>
     <!-- Brand Logo -->
     <div class="brand-logo d-flex align-items-center justify-content-between">
       <a href="./index.html" class="text-nowrap logo-img">
         <img src="../assets/images/logos/logo.svg" alt="" />
       </a>
       <!-- Close Button for Mobile View -->
       <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
         <i class="ti ti-x fs-8"></i>
       </div>
     </div>

     <!-- Sidebar navigation -->
     <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
       <ul id="sidebarnav">
         <!-- Home Section -->
         <li class="nav-small-cap">
           <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
           <span class="hide-menu">Home</span>
         </li>
         <li class="sidebar-item">
           <a class="sidebar-link" href="{{route('dashboard')}}" aria-expanded="false">
             <iconify-icon icon="solar:widget-add-line-duotone"></iconify-icon>
             <span class="hide-menu">Dashboard</span>
           </a>
         </li>
         <!-- Divider -->
         <li>
           <span class="sidebar-divider lg"></span>
         </li>
         <!-- Sensors Section -->
         <li class="nav-small-cap">
           <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
           <span class="hide-menu">SENSORS</span>
         </li>
         <li class="sidebar-item">
           <a class="sidebar-link" href="{{route('temperatures.index')}}" aria-expanded="false">
             <iconify-icon icon="solar:layers-minimalistic-bold-duotone"></iconify-icon>
             <span class="hide-menu">TEMPERATURE</span>
           </a>
         </li>
         <li class="sidebar-item">
           <a class="sidebar-link" href="{{route('humidities.index')}}" aria-expanded="false">
             <iconify-icon icon="solar:danger-circle-line-duotone"></iconify-icon>
             <span class="hide-menu">HUMIDITY</span>
           </a>
         </li>
         <li class="sidebar-item">
           <a class="sidebar-link" href="{{route('intensities.index')}}" aria-expanded="false">
             <iconify-icon icon="solar:bookmark-square-minimalistic-line-duotone"></iconify-icon>
             <span class="hide-menu">INTENSITY</span>
           </a>
         </li>
         <li class="sidebar-item">
           <a class="sidebar-link" href="{{route('moistures.index')}}" aria-expanded="false">
             <iconify-icon icon="solar:file-text-line-duotone"></iconify-icon>
             <span class="hide-menu">MOISTURES</span>
           </a>
         </li>
         <!-- Divider -->
         <li>
           <span class="sidebar-divider lg"></span>
         </li>
         <!-- Actuator Section -->
         <li class="nav-small-cap">
           <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
           <span class="hide-menu">AKTUATOR</span>
         </li>
         <li class="sidebar-item">
           <a class="sidebar-link" href="{{route('actuators.index')}}" aria-expanded="false">
             <iconify-icon icon="solar:login-3-line-duotone"></iconify-icon>
             <span class="hide-menu">AKTUATOR</span>
           </a>
         </li>
         <!-- Divider -->
         <li>
           <span class="sidebar-divider lg"></span>
         </li>
         <!-- Extra Section -->
         <li class="nav-small-cap">
           <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
           <span class="hide-menu">USERS</span>
         </li>
         <li class="sidebar-item">
            <a class="sidebar-link" href="{{route('users.index')}}" aria-expanded="false">
              <iconify-icon icon="solar:user-plus-rounded-line-duotone"></iconify-icon>
              <span class="hide-menu">USER LISTS</span>
            </a>
          </li>
       </ul>
     <!-- End Sidebar navigation -->
   </div>
   <!-- End Sidebar scroll -->
 </aside>
 <!-- Sidebar End -->
</div>
