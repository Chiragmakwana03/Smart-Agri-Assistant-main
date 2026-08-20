    </main>
    </div> <!-- End App Container -->
    
    <script src="js/script.js"></script>
    <footer style="text-align: center; padding: 2rem; color: var(--text-muted); font-size: 0.875rem;">
        &copy; 2026 Agriculture Assistant. All rights reserved. | Helping farmers grow better.
    </footer>

    <!-- Floating AI Chatbot Widget -->
    <?php if (isset($_SESSION['user_id'])): ?>
    <div id="floating-chat-container" style="position: fixed; bottom: 85px; right: 20px; z-index: 9999; font-family: 'Inter', sans-serif;">
        <!-- Chat Toggle Button -->
        <button id="chat-toggle-btn" style="position: fixed; bottom: 20px; right: 20px; width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--primary-light)); color: white; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.25); cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; transition: transform 0.3s ease; z-index: 10000;">
            <i class="fas fa-robot"></i>
        </button>

        <!-- Chat Box Window -->
        <div id="chat-box-window" style="display: none; flex-direction: column; width: 350px; height: 480px; background: white; border-radius: 16px; box-shadow: 0 8px 30px rgba(0,0,0,0.15); border: 1px solid rgba(0,0,0,0.08); overflow: hidden; position: fixed; bottom: 95px; right: 20px; transition: all 0.3s ease; z-index: 10000;">
            <!-- Header -->
            <div style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-robot" style="font-size: 1.2rem;"></i>
                    <div>
                        <h4 style="margin: 0; font-size: 0.95rem; font-weight: 600; font-family: 'Outfit', sans-serif;">AgriAssist AI</h4>
                        <span style="font-size: 0.75rem; opacity: 0.9;">Farming Assistant</span>
                    </div>
                </div>
                <button id="chat-close-btn" style="background: none; border: none; color: white; cursor: pointer; font-size: 1.1rem; opacity: 0.8; transition: opacity 0.2s;"><i class="fas fa-times"></i></button>
            </div>

            <!-- Messages Content -->
            <div id="floating-chat-messages" style="flex: 1; overflow-y: auto; padding: 1rem; display: flex; flex-direction: column; gap: 1rem; background: #fafdfb; font-size: 0.9rem;">
                <div style="display: flex; gap: 8px; align-self: flex-start; max-width: 85%;">
                    <div style="width: 28px; height: 28px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; flex-shrink: 0;"><i class="fas fa-robot"></i></div>
                    <div style="background: white; padding: 0.75rem 1rem; border-radius: 4px 12px 12px 12px; border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                        <p style="margin: 0;">Hi! Ask me any farming questions right here.</p>
                    </div>
                </div>
            </div>

            <!-- Input Box -->
            <form id="floating-chat-form" style="padding: 0.75rem 1rem; background: white; border-top: 1px solid rgba(0,0,0,0.06); display: flex; gap: 8px; align-items: center; margin: 0;">
                <input type="text" id="floating-chat-input" placeholder="Ask AgriAssist..." autocomplete="off" style="flex: 1; padding: 0.6rem 1rem; border: 1px solid #dce4e0; border-radius: 20px; outline: none; font-size: 0.875rem;" />
                <button type="submit" style="background: var(--primary); color: white; border: none; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0;"><i class="fas fa-paper-plane" style="font-size: 0.85rem;"></i></button>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Hide widget if we are on the main ai_assistant.php page to avoid redundancy
        if (window.location.pathname.includes('ai_assistant.php')) {
            const container = document.getElementById('floating-chat-container');
            if (container) container.style.display = 'none';
            return;
        }

        const toggleBtn = document.getElementById('chat-toggle-btn');
        const closeBtn = document.getElementById('chat-close-btn');
        const chatWindow = document.getElementById('chat-box-window');
        const chatForm = document.getElementById('floating-chat-form');
        const chatInput = document.getElementById('floating-chat-input');
        const chatMessages = document.getElementById('floating-chat-messages');

        // Make sure sidebar mobile toggle is moved higher if screen is mobile
        if (window.innerWidth <= 768) {
            // Adjust toggle position slightly so it doesn't conflict with mobile menu toggle
            setTimeout(() => {
                const mobileToggle = document.querySelector('.mobile-toggle-btn');
                if (mobileToggle) {
                    mobileToggle.style.bottom = '85px'; // Move menu button up
                }
            }, 100);
        }

        toggleBtn.addEventListener('click', function() {
            if (chatWindow.style.display === 'none' || chatWindow.style.display === '') {
                chatWindow.style.display = 'flex';
                toggleBtn.style.transform = 'rotate(180deg)';
                toggleBtn.innerHTML = '<i class="fas fa-times"></i>';
                chatInput.focus();
            } else {
                closeChat();
            }
        });

        closeBtn.addEventListener('click', closeChat);

        function closeChat() {
            chatWindow.style.display = 'none';
            toggleBtn.style.transform = 'rotate(0deg)';
            toggleBtn.innerHTML = '<i class="fas fa-robot"></i>';
        }

        function formatMarkdown(text) {
            let escaped = text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
            escaped = escaped.replace(/^\s*[\-\*]\s+(.*)$/gm, '<li>$1</li>');
            escaped = escaped.replace(/(<li>.*<\/li>)/g, '<ul>$1</ul>');
            escaped = escaped.replace(/<\/ul>\s*<ul>/g, '');
            escaped = escaped.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            escaped = escaped.replace(/\n/g, '<br>');
            return escaped;
        }

        function appendMsg(sender, content, isHtml = false) {
            const wrapper = document.createElement("div");
            wrapper.style.display = "flex";
            wrapper.style.gap = "8px";
            wrapper.style.maxWidth = "85%";
            
            if (sender === "user") {
                wrapper.style.alignSelf = "flex-end";
                wrapper.style.flexDirection = "row-reverse";
            } else {
                wrapper.style.alignSelf = "flex-start";
            }

            const avatar = document.createElement("div");
            avatar.style.width = "28px";
            avatar.style.height = "28px";
            avatar.style.borderRadius = "50%";
            avatar.style.display = "flex";
            avatar.style.alignItems = "center";
            avatar.style.justifyContent = "center";
            avatar.style.fontSize = "0.75rem";
            avatar.style.flexShrink = "0";

            if (sender === "user") {
                avatar.style.background = "var(--accent)";
                avatar.style.color = "var(--primary-dark)";
                avatar.innerText = "<?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>";
            } else {
                avatar.style.background = "var(--primary)";
                avatar.style.color = "white";
                avatar.innerHTML = '<i class="fas fa-robot"></i>';
            }

            const bubble = document.createElement("div");
            bubble.style.padding = "0.75rem 1rem";
            bubble.style.boxShadow = "0 1px 3px rgba(0,0,0,0.02)";
            bubble.style.border = "1px solid rgba(0,0,0,0.04)";

            if (sender === "user") {
                bubble.style.background = "var(--primary)";
                bubble.style.color = "white";
                bubble.style.borderRadius = "12px 4px 12px 12px";
            } else {
                bubble.style.background = "white";
                bubble.style.color = "var(--text-main)";
                bubble.style.borderRadius = "4px 12px 12px 12px";
            }

            if (isHtml) {
                bubble.innerHTML = content;
            } else {
                bubble.innerText = content;
            }

            wrapper.appendChild(avatar);
            wrapper.appendChild(bubble);
            chatMessages.appendChild(wrapper);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        chatForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const messageText = chatInput.value.trim();
            if (!messageText) return;

            appendMsg("user", messageText);
            chatInput.value = '';

            // Add typing indicator
            const typingWrapper = document.createElement("div");
            typingWrapper.id = "floating-typing";
            typingWrapper.style.display = "flex";
            typingWrapper.style.gap = "8px";
            typingWrapper.style.alignSelf = "flex-start";
            typingWrapper.innerHTML = `
                <div style="width: 28px; height: 28px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; flex-shrink: 0;"><i class="fas fa-robot"></i></div>
                <div style="background: white; padding: 0.75rem 1rem; border-radius: 4px 12px 12px 12px; border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 1px 3px rgba(0,0,0,0.02);"><span style="color: var(--text-muted); font-style: italic;">Typing...</span></div>
            `;
            chatMessages.appendChild(typingWrapper);
            chatMessages.scrollTop = chatMessages.scrollHeight;

            try {
                const res = await fetch("api/chat.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ message: messageText })
                });

                const data = await res.json();
                const indicator = document.getElementById("floating-typing");
                if (indicator) indicator.remove();

                if (data.success) {
                    appendMsg("assistant", formatMarkdown(data.reply), true);
                } else {
                    appendMsg("assistant", "Error: " + (data.message || data.error || "Unable to reply."), false);
                }
            } catch (err) {
                const indicator = document.getElementById("floating-typing");
                if (indicator) indicator.remove();
                appendMsg("assistant", "Connection error.", false);
            }
        });
    });
    </script>
    <?php endif; ?>
</body>
</html>
