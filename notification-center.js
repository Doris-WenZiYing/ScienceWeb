/**
 * 通知中心模組 - 適用於所有教師頁面
 * 統一管理通知的加載、顯示、已讀標記和刪除
 */

class NotificationCenter {
  constructor(options) {

    if (!options || !options.apiUrl) {
      throw new Error('NotificationCenter needs an apiUrl in the options object.');
    }

    this.notifications = [];
    this.apiUrl = options.apiUrl;
    this.refreshInterval = options.refreshInterval || 120000; // 允許覆蓋
    this.autoRefreshTimer = null;
  }

  /**
   * 初始化通知中心
   */
  async init() {
    await this.loadNotifications();
    this.setupEventListeners();
    this.startAutoRefresh();
  }

  /**
   * 從API加載通知
   */
  async loadNotifications() {
    try {
      const response = await fetch(`${this.apiUrl}?action=get_notifications&limit=20`);
      const result = await response.json();

      if (result.success) {
        this.notifications = result.data;
        this.renderNotifications();
        this.updateBadge();
      } else {
        console.error('加載通知失敗:', result.message);
        this.showToast('加載通知失敗', 'error');
      }
    } catch (error) {
      console.error('通知API請求失敗:', error);
      this.showToast('網路連線錯誤', 'error');
    }
  }

  /**
   * 渲染通知列表
   */
  renderNotifications() {
    const notificationList = document.getElementById('notificationList');
    
    if (!notificationList) {
      console.warn('找不到通知列表元素 #notificationList');
      return;
    }

    if (this.notifications.length === 0) {
      notificationList.innerHTML = `
        <div class="empty-notification">
          <i class="fas fa-inbox"></i>
          <p>沒有通知</p>
        </div>
      `;
      return;
    }

    notificationList.innerHTML = this.notifications.map(notification => {
      const isUnread = notification.is_read === 0 || notification.is_read === '0';
      const icon = notification.icon || 'bell';
      const typeLabel = notification.type_label || '系統通知';
      
      return `
        <div class="notification-item ${isUnread ? 'unread' : ''}" 
             data-notification-id="${notification.notification_id}"
             data-link="${notification.link || ''}">
          <div class="notification-item-header">
            <div class="notification-item-title">
              <span class="notification-type-icon info">
                <i class="fas fa-${icon}"></i>
              </span>
              ${notification.title}
            </div>
            <div class="notification-item-time">${notification.time_ago}</div>
          </div>
          <div class="notification-item-message">${notification.message}</div>
          <div class="notification-item-type">
            <span class="type-badge">${typeLabel}</span>
          </div>
          <div class="notification-actions">
            ${notification.link ? `
              <button class="notification-btn notification-btn-read" 
                      onclick="event.stopPropagation(); notificationCenter.handleNotificationClick(${notification.notification_id}, '${notification.link}')">
                <i class="fas fa-eye"></i> 查看
              </button>
            ` : ''}
            <button class="notification-btn notification-btn-clear" 
                    onclick="event.stopPropagation(); notificationCenter.deleteNotification(${notification.notification_id})">
              <i class="fas fa-trash"></i> 刪除
            </button>
          </div>
        </div>
      `;
    }).join('');

    this.setupNotificationItemListeners(notificationList);
  }

  setupNotificationItemListeners(container) {
    container.querySelectorAll('.notification-item').forEach(item => {
      
      // 監聽整個 item 的點擊 (用於查看)
      item.addEventListener('click', (e) => {
        // 檢查點擊的是否是按鈕，如果是，則不觸發 item 的點擊
        if (e.target.closest('[data-action]')) {
          return;
        }
        
        const id = item.dataset.notificationId;
        const link = item.dataset.link;
        // 這裡的 'this' 會正確指向 NotificationCenter 實例
        this.handleNotificationClick(id, link);
      });

      // 監聽按鈕的點擊 (查看 或 刪除)
      item.querySelectorAll('[data-action]').forEach(button => {
        button.addEventListener('click', (e) => {
          e.stopPropagation(); // 阻止事件冒泡到父層 (item)
          
          const id = item.dataset.notificationId;
          const link = item.dataset.link;
          const action = button.dataset.action;

          if (action === 'view') {
            this.handleNotificationClick(id, link);
          } else if (action === 'delete') {
            this.deleteNotification(id);
          }
        });
      });
    });
  }

  /**
   * 更新通知徽章
   */
  updateBadge() {
    const badge = document.getElementById('notificationBadge');
    if (!badge) return;

    const unreadCount = this.notifications.filter(n => 
      n.is_read === 0 || n.is_read === '0'
    ).length;

    if (unreadCount > 0) {
      badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
      badge.classList.add('active');
    } else {
      badge.classList.remove('active');
    }
  }

  /**
   * 處理通知點擊事件
   */
  async handleNotificationClick(notificationId, link) {
    // 標記為已讀
    await this.markAsRead(notificationId);
    
    // 如果有連結，跳轉頁面
    if (link && link !== 'null' && link !== '') {
      window.location.href = link;
    }
  }

  /**
   * 標記單個通知為已讀
   */
  async markAsRead(notificationId) {
    try {
      const formData = new FormData();
      formData.append('action', 'mark_notification_read');
      formData.append('notification_id', notificationId);

      const response = await fetch(this.apiUrl, {
        method: 'POST',
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        // 更新本地狀態
        const notification = this.notifications.find(n => n.notification_id == notificationId);
        if (notification) {
          notification.is_read = 1;
          this.renderNotifications();
          this.updateBadge();
        }
      } else {
        console.error('標記已讀失敗:', result.message);
      }
    } catch (error) {
      console.error('標記已讀API請求失敗:', error);
    }
  }

  /**
   * 標記所有通知為已讀
   */
  async markAllAsRead() {
    try {
      const formData = new FormData();
      formData.append('action', 'mark_all_notifications_read');

      const response = await fetch(this.apiUrl, {
        method: 'POST',
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        // 更新本地狀態
        this.notifications.forEach(n => n.is_read = 1);
        this.renderNotifications();
        this.updateBadge();
        this.showToast('全部已標記為已讀', 'success');
      } else {
        this.showToast('操作失敗', 'error');
      }
    } catch (error) {
      console.error('全部標記已讀失敗:', error);
      this.showToast('操作失敗', 'error');
    }
  }

  /**
   * 刪除通知
   */
  async deleteNotification(notificationId) {
    if (!confirm('確定要刪除此通知嗎？')) {
      return;
    }

    try {
      const formData = new FormData();
      formData.append('action', 'delete_notification');
      formData.append('notification_id', notificationId);

      const response = await fetch(this.apiUrl, {
        method: 'POST',
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        // 從本地移除
        this.notifications = this.notifications.filter(n => n.notification_id != notificationId);
        this.renderNotifications();
        this.updateBadge();
        this.showToast('通知已刪除', 'success');
      } else {
        this.showToast('刪除失敗', 'error');
      }
    } catch (error) {
      console.error('刪除通知失敗:', error);
      this.showToast('刪除失敗', 'error');
    }
  }

  /**
   * 設置事件監聽器
   */
  setupEventListeners() {
    const notificationBell = document.getElementById('notificationBell');
    const notificationPanel = document.getElementById('notificationPanel');
    const markAllReadBtn = document.querySelector('.mark-all-read');
    const avatar = document.getElementById('avatar');
    const dropdown = document.getElementById('dropdown');

    // 通知鈴鐺點擊
    if (notificationBell) {
      notificationBell.addEventListener('click', (e) => {
        e.stopPropagation();
        notificationPanel?.classList.toggle('active');
        if (dropdown) dropdown.style.display = 'none';
      });
    }

    // 標記全部已讀按鈕
    if (markAllReadBtn) {
      markAllReadBtn.addEventListener('click', () => {
        this.markAllAsRead();
      });
    }

    // 頭像下拉選單
    if (avatar) {
      avatar.addEventListener('click', (e) => {
        e.stopPropagation();
        if (dropdown) {
          dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }
        notificationPanel?.classList.remove('active');
      });
    }

    // 點擊外部關閉
    window.addEventListener('click', (e) => {
      if (avatar && dropdown && !avatar.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.style.display = 'none';
      }
      if (notificationBell && notificationPanel && 
          !notificationBell.contains(e.target) && !notificationPanel.contains(e.target)) {
        notificationPanel.classList.remove('active');
      }
    });
  }

  /**
   * 開始自動刷新
   */
  startAutoRefresh() {
    if (this.autoRefreshTimer) {
      clearInterval(this.autoRefreshTimer);
    }
    
    this.autoRefreshTimer = setInterval(() => {
      this.loadNotifications();
    }, this.refreshInterval);
  }

  /**
   * 停止自動刷新
   */
  stopAutoRefresh() {
    if (this.autoRefreshTimer) {
      clearInterval(this.autoRefreshTimer);
      this.autoRefreshTimer = null;
    }
  }

  /**
   * 顯示提示訊息
   */
  showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.style.cssText = `
      position: fixed;
      top: 20px;
      right: 20px;
      background: ${
        type === 'success' ? 'linear-gradient(135deg, #28a745 0%, #20c997 100%)' :
        type === 'error' ? 'linear-gradient(135deg, #dc3545 0%, #e83e8c 100%)' :
        'linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%)'
      };
      color: white;
      padding: 15px 25px;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      z-index: 10000;
      font-weight: 500;
      animation: slideIn 0.3s ease;
      cursor: pointer;
    `;
    toast.textContent = message;

    toast.addEventListener('click', () => {
      toast.style.animation = 'slideOut 0.3s ease';
      setTimeout(() => toast.remove(), 300);
    });

    document.body.appendChild(toast);

    setTimeout(() => {
      if (toast.parentNode) {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
      }
    }, 3000);
  }

  /**
   * 手動刷新通知
   */
  async refresh() {
    await this.loadNotifications();
    this.showToast('通知已更新', 'success');
  }
}

let notificationCenter;
document.addEventListener('DOMContentLoaded', () => {
  let userApiUrl;

  // --- 這是您需要客製化的地方 ---
  // 判斷當前使用者類型
  if (document.body.classList.contains('teacher-page')) {
    userApiUrl = 'teacher_api.php';
  } else if (document.body.classList.contains('student-page')) {
    userApiUrl = 'student_api.php';
  } else if (document.body.classList.contains('sci-page')) {
    userApiUrl = 'api.php';
  } else {
    console.error('無法確定使用者類型，通知中心無法初始化。');
    return;
  }
  // --- 判斷結束 ---

  // 根據判斷結果，建立對應的 NotificationCenter 實例
  notificationCenter = new NotificationCenter({
    apiUrl: userApiUrl
  });
  
  // 初始化
  notificationCenter.init();
});

// 添加必要的CSS動畫
if (!document.querySelector('#notificationCenterStyles')) {
  const style = document.createElement('style');
  style.id = 'notificationCenterStyles';
  style.textContent = `
    @keyframes slideIn {
      from { transform: translateX(100%); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
      from { transform: translateX(0); opacity: 1; }
      to { transform: translateX(100%); opacity: 0; }
    }
    .notification-item-type {
      margin-top: 8px;
    }
    .type-badge {
      display: inline-block;
      padding: 4px 10px;
      background: rgba(17, 153, 142, 0.1);
      color: #11998e;
      border-radius: 12px;
      font-size: 11px;
      font-weight: 600;
    }
  `;
  document.head.appendChild(style);
}