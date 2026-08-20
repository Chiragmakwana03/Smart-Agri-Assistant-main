<?php 
require_once 'includes/db_connect.php';
include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="dashboard-header" style="margin-bottom: 1.5rem;">
    <h2>AgriAssist AI Chatbot</h2>
    <p style="color: var(--text-muted);">Get instant answers to crop diseases, soil health, weather advice, and farming best practices.</p>
</div>

<div style="max-width: 900px; margin: 0 auto;">
    <!-- API Setup Notice -->
    <div id="setupNotice" class="glass-card" style="display: none; border-left: 5px solid #e74c3c; margin-bottom: 1.5rem; background: #fdf2f2;">
        <h3 style="color: #c0392b; margin-bottom: 0.5rem;"><i class="fas fa-exclamation-triangle"></i> Action Required</h3>
        <p id="setupNoticeText" style="color: #7f8c8d;"></p>
        <p style="margin-top: 1rem; font-size: 0.9rem; color: #34495e;">
            <strong>To set it up:</strong> Open the file <code style="background: #eee; padding: 2px 6px; border-radius: 4px;">config.php</code> in the project root directory and update <code style="background: #eee; padding: 2px 6px; border-radius: 4px;">GEMINI_API_KEY</code> with your API key from Google AI Studio.
        </p>
    </div>

    <!-- Main Chat Card -->
    <div class="glass-card" style="padding: 0; overflow: hidden; border: 1px solid rgba(0,0,0,0.08); display: flex; flex-direction: column; height: 600px; max-height: 70vh;">
        <!-- Chat Header -->
        <div style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 15px; border-bottom: 1px solid rgba(0,0,0,0.05);">
            <div style="width: 45px; height: 45px; background: rgba(255, 255, 255, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="fas fa-robot"></i>
            </div>
            <div>
                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 600;">AgriAssist Expert AI</h3>
                <div style="display: flex; align-items: center; gap: 6px; font-size: 0.8rem; opacity: 0.9;">
                    <span style="width: 8px; height: 8px; background: #2ecc71; border-radius: 50%; display: inline-block;"></span>
                    <span>Ready to help you farm smarter</span>
                </div>
            </div>
        </div>

        <!-- Chat Messages Container -->
        <div id="chatMessages" style="flex: 1; overflow-y: auto; padding: 1.5rem; display: flex; flex-direction: column; gap: 1.25rem; background: #fafdfb;">
            <!-- Welcome message -->
            <div class="message-wrapper assistant" style="display: flex; gap: 12px; max-width: 80%;">
                <div class="chat-avatar" style="width: 32px; height: 32px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; flex-shrink: 0;">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="chat-bubble" style="background: white; padding: 1rem 1.25rem; border-radius: 4px 16px 16px 16px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); border: 1px solid rgba(0,0,0,0.04);">
                    <p style="margin: 0; font-size: 0.95rem;">Hello! I am your AI farming assistant. How can I help you today? You can ask me about:</p>
                    <ul style="margin: 8px 0 0 1.2rem; padding: 0; font-size: 0.95rem;">
                        <li>Diagnosing crop leaf diseases</li>
                        <li>Soil preparation and fertilizer recommendations</li>
                        <li>Selecting optimal crops for your region</li>
                        <li>Irrigation methods and scheduling</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Suggestions Section -->
        <div id="suggestionsBox" style="padding: 0.75rem 1.5rem; background: #f4faf6; border-top: 1px solid rgba(0,0,0,0.03); display: flex; flex-wrap: wrap; gap: 8px;">
            <span style="font-size: 0.8rem; color: var(--text-muted); width: 100%; margin-bottom: 2px;">Frequently Asked:</span>
            <button class="suggest-btn" onclick="sendSuggestion('What are the best organic fertilizers for wheat crop?')">Wheat Fertilizers</button>
            <button class="suggest-btn" onclick="sendSuggestion('How do I control whitefly infestation in cotton fields?')">Cotton Pest Control</button>
            <button class="suggest-btn" onclick="sendSuggestion('Suggest crops suitable for black soil with low water availability.')">Black Soil Crops</button>
            <button class="suggest-btn" onclick="sendSuggestion('What is crop rotation and how does it benefit soil fertility?')">Crop Rotation</button>
        </div>

        <!-- Chat Input Form -->
        <form id="chatForm" style="padding: 1rem 1.5rem; background: white; border-top: 1px solid rgba(0,0,0,0.06); display: flex; gap: 10px; align-items: center;">
            <input type="text" id="userInput" placeholder="Ask anything about farming..." autocomplete="off" style="flex: 1; padding: 0.8rem 1.2rem; border: 1px solid #dce4e0; border-radius: 25px; outline: none; font-size: 0.95rem; transition: border 0.2s;" />
            <button type="submit" style="background: var(--primary); color: white; border: none; width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.2s; flex-shrink: 0;">
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>
</div>

<style>
    .suggest-btn {
        background: white;
        border: 1px solid #cbdcd2;
        color: var(--primary);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.825rem;
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: 500;
    }
    .suggest-btn:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    .chat-bubble p {
        margin-bottom: 0.5rem;
    }
    .chat-bubble p:last-child {
        margin-bottom: 0;
    }
    .chat-bubble ul, .chat-bubble ol {
        margin-bottom: 0.5rem;
        padding-left: 1.25rem;
    }
    .chat-bubble ul:last-child, .chat-bubble ol:last-child {
        margin-bottom: 0;
    }
    #userInput:focus {
        border-color: var(--primary);
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const chatForm = document.getElementById("chatForm");
    const userInput = document.getElementById("userInput");
    const chatMessages = document.getElementById("chatMessages");
    const setupNotice = document.getElementById("setupNotice");
    const setupNoticeText = document.getElementById("setupNoticeText");
    const suggestionsBox = document.getElementById("suggestionsBox");

    // Format basic Markdown-like responses from Gemini
    function formatMarkdown(text) {
        // Safe escape HTML
        let escaped = text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;");
        
        // Bullet points
        escaped = escaped.replace(/^\s*[\-\*]\s+(.*)$/gm, '<li>$1</li>');
        escaped = escaped.replace(/(<li>.*<\/li>)/g, '<ul>$1</ul>');
        // Merge adjacent <ul> blocks
        escaped = escaped.replace(/<\/ul>\s*<ul>/g, '');

        // Bold text **bold**
        escaped = escaped.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        
        // Line breaks
        escaped = escaped.replace(/\n/g, '<br>');

        return escaped;
    }

    function appendMessage(sender, content, isHtml = false) {
        const wrapper = document.createElement("div");
        wrapper.classList.add("message-wrapper", sender);
        wrapper.style.display = "flex";
        wrapper.style.gap = "12px";
        wrapper.style.maxWidth = "80%";
        
        if (sender === "user") {
            wrapper.style.alignSelf = "flex-end";
            wrapper.style.flexDirection = "row-reverse";
        } else {
            wrapper.style.alignSelf = "flex-start";
        }

        const avatar = document.createElement("div");
        avatar.style.width = "32px";
        avatar.style.height = "32px";
        avatar.style.borderRadius = "50%";
        avatar.style.display = "flex";
        avatar.style.alignItems = "center";
        avatar.style.justify = "center";
        avatar.style.fontSize = "0.9rem";
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
        bubble.style.padding = "1rem 1.25rem";
        bubble.style.fontSize = "0.95rem";
        bubble.style.boxShadow = "0 2px 10px rgba(0,0,0,0.02)";
        bubble.style.border = "1px solid rgba(0,0,0,0.04)";

        if (sender === "user") {
            bubble.style.background = "var(--primary)";
            bubble.style.color = "white";
            bubble.style.borderRadius = "16px 4px 16px 16px";
        } else {
            bubble.style.background = "white";
            bubble.style.color = "var(--text-main)";
            bubble.style.borderRadius = "4px 16px 16px 16px";
        }

        if (isHtml) {
            bubble.innerHTML = content;
        } else {
            bubble.innerText = content;
        }

        wrapper.appendChild(avatar);
        wrapper.appendChild(bubble);
        chatMessages.appendChild(wrapper);
        
        // Scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function appendTypingIndicator() {
        const id = "typing-indicator";
        const wrapper = document.createElement("div");
        wrapper.id = id;
        wrapper.style.display = "flex";
        wrapper.style.gap = "12px";
        wrapper.style.maxWidth = "80%";
        wrapper.style.alignSelf = "flex-start";

        const avatar = document.createElement("div");
        avatar.style.width = "32px";
        avatar.style.height = "32px";
        avatar.style.borderRadius = "50%";
        avatar.style.background = "var(--primary)";
        avatar.style.color = "white";
        avatar.style.display = "flex";
        avatar.style.alignItems = "center";
        avatar.style.justifyContent = "center";
        avatar.style.fontSize = "0.9rem";
        avatar.style.flexShrink = "0";
        avatar.innerHTML = '<i class="fas fa-robot"></i>';

        const bubble = document.createElement("div");
        bubble.style.padding = "1rem 1.25rem";
        bubble.style.background = "white";
        bubble.style.borderRadius = "4px 16px 16px 16px";
        bubble.style.boxShadow = "0 2px 10px rgba(0,0,0,0.02)";
        bubble.style.border = "1px solid rgba(0,0,0,0.04)";
        bubble.innerHTML = '<span style="color: var(--text-muted); font-style: italic;">AgriAssist AI is typing...</span>';

        wrapper.appendChild(avatar);
        wrapper.appendChild(bubble);
        chatMessages.appendChild(wrapper);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        return id;
    }

    async function sendMessage(msgText) {
        if (!msgText.trim()) return;

        appendMessage("user", msgText);
        userInput.value = "";
        suggestionsBox.style.display = "none"; // Hide suggestion panel once user asks a query

        const typingId = appendTypingIndicator();

        try {
            const response = await fetch("api/chat.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ message: msgText })
            });

            const data = await response.json();
            
            // Remove typing indicator
            const typingIndicator = document.getElementById(typingId);
            if (typingIndicator) typingIndicator.remove();

            if (data.setup_required) {
                // Show API Key Warning
                setupNoticeText.innerText = data.message;
                setupNotice.style.display = "block";
                appendMessage("assistant", "System Error: Gemini API key has not been configured. Please follow the instructions at the top of the page.", false);
            } else if (data.success) {
                appendMessage("assistant", formatMarkdown(data.reply), true);
            } else {
                appendMessage("assistant", "Sorry, I encountered an error: " + (data.error || "Unknown error occurred."), false);
            }
        } catch (err) {
            const typingIndicator = document.getElementById(typingId);
            if (typingIndicator) typingIndicator.remove();
            appendMessage("assistant", "Could not connect to the assistant server. Please check your connection.", false);
            console.error(err);
        }
    }

    chatForm.addEventListener("submit", function(e) {
        e.preventDefault();
        const msg = userInput.value;
        sendMessage(msg);
    });

    window.sendSuggestion = function(text) {
        sendMessage(text);
    };
});
</script>

<?php include 'includes/footer.php'; ?>
