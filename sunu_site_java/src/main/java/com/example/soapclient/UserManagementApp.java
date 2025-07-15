package com.example.soapclient;

import javax.swing.*;
import javax.swing.border.EmptyBorder;
import javax.swing.border.LineBorder;
import javax.swing.border.SoftBevelBorder;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.ComponentAdapter;
import java.awt.event.ComponentEvent;
import java.util.List;
import com.soapclient.services.*;

public class UserManagementApp {
    // Color scheme (unchanged)
    private static final Color PRIMARY_BLUE = new Color(25, 45, 90);
    private static final Color SECONDARY_BLUE = new Color(59, 130, 246);
    private static final Color SUCCESS_GREEN = new Color(34, 197, 94);
    private static final Color DANGER_RED = new Color(239, 68, 68);
    private static final Color WARNING_ORANGE = new Color(245, 158, 11);
    private static final Color LIGHT_BACKGROUND = new Color(240, 242, 245);
    private static final Color PANEL_BACKGROUND = Color.WHITE;
    private static final Color TEXT_DARK = new Color(31, 41, 55);
    private static final Color TEXT_LIGHT = new Color(107, 114, 128);
    private static final Color BORDER_GRAY = new Color(220, 220, 220);
    private static final Color SHADOW_COLOR = new Color(0, 0, 0, 20);

    private JFrame frame;
    private JTextField loginField;
    private JPasswordField passwordField;
    private JButton loginButton;
    private JTextArea resultArea;
    private UserServicePortType port;
    private String token;
    private JPanel adminPanel;
    private JLabel titleLabel;
    private JPanel contentWrapperPanel;
    private JPanel loginSectionPanel;
    private JPanel resultsSectionPanel;
    private String userRole;
    private String userLogin;

    // Responsive scaling factors
    private double scaleFactor = 1.0; // Adjusted based on window size
    private final double BASE_WIDTH = 1050.0; // Reference width for scaling
    private final double BASE_HEIGHT = 850.0; // Reference height for scaling

    public UserManagementApp() {
        System.setProperty("awt.useSystemAAFontSettings", "on");
        System.setProperty("swing.aatext", "true");
        initialize();
    }

    private void initialize() {
        frame = new JFrame();
        frame.setTitle("Gestion des Utilisateurs - ESP Dakar");
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frame.getContentPane().setLayout(new BorderLayout());
        frame.getContentPane().setBackground(LIGHT_BACKGROUND);

        // Add component listener to handle window resizing
        frame.addComponentListener(new ComponentAdapter() {
            @Override
            public void componentResized(ComponentEvent e) {
                updateScaleFactor();
                updateComponentSizes();
            }
        });

        JPanel headerPanel = createHeaderPanel();
        frame.getContentPane().add(headerPanel, BorderLayout.NORTH);

        contentWrapperPanel = new JPanel(new BorderLayout());
        contentWrapperPanel.setBackground(LIGHT_BACKGROUND);
        contentWrapperPanel.setBorder(BorderFactory.createCompoundBorder(
                new EmptyBorder(getScaledSize(30), getScaledSize(30), getScaledSize(30), getScaledSize(30)),
                BorderFactory.createCompoundBorder(
                    new LineBorder(BORDER_GRAY, 1, true),
                    new SoftBevelBorder(SoftBevelBorder.RAISED, PANEL_BACKGROUND, SHADOW_COLOR)
                )
        ));
        contentWrapperPanel.setOpaque(true);

        loginSectionPanel = createStyledLoginPanel();
        contentWrapperPanel.add(loginSectionPanel, BorderLayout.NORTH);

        resultsSectionPanel = createResultsPanel();
        contentWrapperPanel.add(resultsSectionPanel, BorderLayout.CENTER);

        frame.getContentPane().add(contentWrapperPanel, BorderLayout.CENTER);

        initializeService();

        frame.setMinimumSize(new Dimension(600, 400)); // Smaller minimum size for flexibility
        frame.pack();
        frame.setLocationRelativeTo(null);
        frame.setVisible(true);
    }

    private void updateScaleFactor() {
        Dimension size = frame.getSize();
        scaleFactor = Math.min(size.getWidth() / BASE_WIDTH, size.getHeight() / BASE_HEIGHT);
        if (scaleFactor < 0.5) scaleFactor = 0.5; // Prevent excessive shrinking
        if (scaleFactor > 1.5) scaleFactor = 1.5; // Prevent excessive growing
    }

    private int getScaledSize(int baseSize) {
        return (int) (baseSize * scaleFactor);
    }

    private Font getScaledFont(String fontName, int style, int baseSize) {
        return new Font(fontName, style, getScaledSize(baseSize));
    }

    private void updateComponentSizes() {
        // Update header panel
        titleLabel.setFont(getScaledFont("SansSerif", Font.BOLD, 28));
        JPanel headerPanel = (JPanel) titleLabel.getParent().getParent();
        headerPanel.setBorder(BorderFactory.createCompoundBorder(
                BorderFactory.createMatteBorder(0, 0, 1, 0, new Color(0, 0, 0, 50)),
                new EmptyBorder(getScaledSize(25), getScaledSize(35), getScaledSize(25), getScaledSize(35))));

        // Update login panel if present
        if (loginSectionPanel != null) {
            loginSectionPanel.setBorder(BorderFactory.createCompoundBorder(
                    new LineBorder(BORDER_GRAY, 1, true),
                    new EmptyBorder(getScaledSize(35), getScaledSize(40), getScaledSize(35), getScaledSize(40))));
        }

        // Update admin panel if present
        if (adminPanel != null) {
            adminPanel.setBorder(BorderFactory.createCompoundBorder(
                    new LineBorder(BORDER_GRAY, 1, true),
                    new EmptyBorder(getScaledSize(30), getScaledSize(35), getScaledSize(30), getScaledSize(35))));
        }

        // Update results panel
        resultsSectionPanel.setBorder(BorderFactory.createCompoundBorder(
                new LineBorder(BORDER_GRAY, 1, true),
                new EmptyBorder(getScaledSize(25), getScaledSize(25), getScaledSize(25), getScaledSize(25))));

        contentWrapperPanel.revalidate();
        contentWrapperPanel.repaint();
    }

    private JPanel createHeaderPanel() {
        JPanel headerPanel = new JPanel(new BorderLayout());
        headerPanel.setBackground(PRIMARY_BLUE);
        headerPanel.setBorder(BorderFactory.createCompoundBorder(
                BorderFactory.createMatteBorder(0, 0, 1, 0, new Color(0, 0, 0, 50)),
                new EmptyBorder(getScaledSize(25), getScaledSize(35), getScaledSize(25), getScaledSize(35))));

        titleLabel = new JLabel("École Supérieure Polytechnique de Dakar");
        titleLabel.setFont(getScaledFont("SansSerif", Font.BOLD, 28));
        titleLabel.setForeground(PANEL_BACKGROUND);
        titleLabel.setIcon(createCircleIcon());

        JLabel subtitleLabel = new JLabel("Système de Gestion des Utilisateurs");
        subtitleLabel.setFont(getScaledFont("SansSerif", Font.PLAIN, 17));
        subtitleLabel.setForeground(new Color(191, 219, 254));

        JPanel titlePanel = new JPanel(new BorderLayout());
        titlePanel.setBackground(PRIMARY_BLUE);
        titlePanel.add(titleLabel, BorderLayout.NORTH);
        titlePanel.add(subtitleLabel, BorderLayout.SOUTH);

        headerPanel.add(titlePanel, BorderLayout.WEST);
        return headerPanel;
    }

    private Icon createCircleIcon() {
        return new Icon() {
            @Override
            public void paintIcon(Component c, Graphics g, int x, int y) {
                Graphics2D g2 = (Graphics2D) g.create();
                g2.setRenderingHint(RenderingHints.KEY_ANTIALIASING, RenderingHints.VALUE_ANTIALIAS_ON);
                g2.setColor(PANEL_BACKGROUND);
                g2.fillOval(x, y, getIconWidth(), getIconHeight());
                g2.setColor(PRIMARY_BLUE);
                g2.setStroke(new BasicStroke(2));
                g2.drawOval(x + 2, y + 2, getIconWidth() - 4, getIconHeight() - 4);
                g2.setColor(PRIMARY_BLUE);
                g2.setFont(getScaledFont("SansSerif", Font.BOLD, 13));
                FontMetrics fm = g2.getFontMetrics();
                String text = "ESP";
                int textWidth = fm.stringWidth(text);
                int textHeight = fm.getAscent();
                g2.drawString(text, x + (getIconWidth() - textWidth) / 2, y + (getIconHeight() + textHeight) / 2 - 2);
                g2.dispose();
            }

            @Override
            public int getIconWidth() { return getScaledSize(40); }
            @Override
            public int getIconHeight() { return getScaledSize(40); }
        };
    }

    private JPanel createStyledLoginPanel() {
        JPanel loginPanel = new JPanel();
        loginPanel.setLayout(new BoxLayout(loginPanel, BoxLayout.Y_AXIS));
        loginPanel.setBackground(PANEL_BACKGROUND);
        loginPanel.setBorder(BorderFactory.createCompoundBorder(
                new LineBorder(BORDER_GRAY, 1, true),
                new EmptyBorder(getScaledSize(35), getScaledSize(40), getScaledSize(35), getScaledSize(40))));
        loginPanel.setOpaque(true);

        JLabel loginTitle = new JLabel("🔐 Authentification");
        loginTitle.setFont(getScaledFont("SansSerif", Font.BOLD, 22));
        loginTitle.setForeground(TEXT_DARK);
        loginTitle.setAlignmentX(Component.CENTER_ALIGNMENT);

        JPanel formPanel = new JPanel(new GridBagLayout());
        formPanel.setBackground(PANEL_BACKGROUND);
        GridBagConstraints gbc = new GridBagConstraints();
        gbc.insets = new Insets(getScaledSize(18), getScaledSize(18), getScaledSize(18), getScaledSize(18));

        JLabel lblLogin = createStyledLabel("👤 Nom d'utilisateur:");
        gbc.gridx = 0; gbc.gridy = 0; gbc.anchor = GridBagConstraints.WEST;
        formPanel.add(lblLogin, gbc);

        loginField = createStyledTextField();
        loginField.setToolTipText("Entrez votre nom d'utilisateur");
        gbc.gridx = 1; gbc.gridy = 0; gbc.fill = GridBagConstraints.HORIZONTAL; gbc.weightx = 1.0;
        formPanel.add(loginField, gbc);

        JLabel lblPassword = createStyledLabel("🔒 Mot de passe:");
        gbc.gridx = 0; gbc.gridy = 1; gbc.fill = GridBagConstraints.NONE; gbc.weightx = 0;
        formPanel.add(lblPassword, gbc);

        passwordField = createStyledPasswordField();
        passwordField.setToolTipText("Entrez votre mot de passe");
        gbc.gridx = 1; gbc.gridy = 1; gbc.fill = GridBagConstraints.HORIZONTAL; gbc.weightx = 1.0;
        formPanel.add(passwordField, gbc);

        loginButton = createPrimaryButton("🚀 Se connecter");
        loginButton.addActionListener(e -> authenticateUser());
        gbc.gridx = 0; gbc.gridy = 2; gbc.gridwidth = 2; gbc.fill = GridBagConstraints.HORIZONTAL;
        gbc.insets = new Insets(getScaledSize(30), getScaledSize(18), getScaledSize(18), getScaledSize(18));
        formPanel.add(loginButton, gbc);

        loginPanel.add(loginTitle);
        loginPanel.add(Box.createVerticalStrut(getScaledSize(30)));
        loginPanel.add(formPanel);

        return loginPanel;
    }

    private JPanel createResultsPanel() {
        JPanel resultsPanel = new JPanel(new BorderLayout());
        resultsPanel.setBackground(PANEL_BACKGROUND);
        resultsPanel.setBorder(BorderFactory.createCompoundBorder(
                new LineBorder(BORDER_GRAY, 1, true),
                new EmptyBorder(getScaledSize(25), getScaledSize(25), getScaledSize(25), getScaledSize(25))));
        resultsPanel.setOpaque(true);

        JLabel resultsTitle = new JLabel("📊 Résultats et Logs");
        resultsTitle.setFont(getScaledFont("SansSerif", Font.BOLD, 18));
        resultsTitle.setForeground(TEXT_DARK);
        resultsPanel.add(resultsTitle, BorderLayout.NORTH);

        resultArea = new JTextArea();
        resultArea.setEditable(false);
        resultArea.setFont(getScaledFont("Monospaced", Font.PLAIN, 14));
        resultArea.setBackground(new Color(247, 250, 252));
        resultArea.setBorder(new EmptyBorder(getScaledSize(20), getScaledSize(20), getScaledSize(20), getScaledSize(20)));
        resultArea.setForeground(TEXT_DARK);
        resultArea.setLineWrap(true);
        resultArea.setWrapStyleWord(true);

        JScrollPane scrollPane = new JScrollPane(resultArea);
        scrollPane.setBorder(new LineBorder(BORDER_GRAY, 1, true));
        scrollPane.getVerticalScrollBar().setUnitIncrement(16);
        resultsPanel.add(scrollPane, BorderLayout.CENTER);

        return resultsPanel;
    }

    private JLabel createStyledLabel(String text) {
        JLabel label = new JLabel(text);
        label.setFont(getScaledFont("SansSerif", Font.PLAIN, 16));
        label.setForeground(TEXT_DARK);
        return label;
    }

    private JTextField createStyledTextField() {
        JTextField field = new JTextField(25);
        field.setFont(getScaledFont("SansSerif", Font.PLAIN, 16));
        field.setBorder(BorderFactory.createCompoundBorder(
                new LineBorder(BORDER_GRAY, 1, true),
                new EmptyBorder(getScaledSize(14), getScaledSize(18), getScaledSize(14), getScaledSize(18))));
        field.setBackground(PANEL_BACKGROUND);
        field.addFocusListener(new java.awt.event.FocusAdapter() {
            public void focusGained(java.awt.event.FocusEvent evt) {
                field.setBorder(BorderFactory.createCompoundBorder(
                        new LineBorder(SECONDARY_BLUE, 2, true),
                        new EmptyBorder(getScaledSize(13), getScaledSize(17), getScaledSize(13), getScaledSize(17))));
            }
            public void focusLost(java.awt.event.FocusEvent evt) {
                field.setBorder(BorderFactory.createCompoundBorder(
                        new LineBorder(BORDER_GRAY, 1, true),
                        new EmptyBorder(getScaledSize(14), getScaledSize(18), getScaledSize(14), getScaledSize(18))));
            }
        });
        return field;
    }

    private JPasswordField createStyledPasswordField() {
        JPasswordField field = new JPasswordField(25);
        field.setFont(getScaledFont("SansSerif", Font.PLAIN, 16));
        field.setBorder(BorderFactory.createCompoundBorder(
                new LineBorder(BORDER_GRAY, 1, true),
                new EmptyBorder(getScaledSize(14), getScaledSize(18), getScaledSize(14), getScaledSize(18))));
        field.setBackground(PANEL_BACKGROUND);
        field.addFocusListener(new java.awt.event.FocusAdapter() {
            public void focusGained(java.awt.event.FocusEvent evt) {
                field.setBorder(BorderFactory.createCompoundBorder(
                        new LineBorder(SECONDARY_BLUE, 2, true),
                        new EmptyBorder(getScaledSize(13), getScaledSize(17), getScaledSize(13), getScaledSize(17))));
            }
            public void focusLost(java.awt.event.FocusEvent evt) {
                field.setBorder(BorderFactory.createCompoundBorder(
                        new LineBorder(BORDER_GRAY, 1, true),
                        new EmptyBorder(getScaledSize(14), getScaledSize(18), getScaledSize(14), getScaledSize(18))));
            }
        });
        return field;
    }

    private JButton createPrimaryButton(String text) {
        JButton button = new JButton(text);
        button.setFont(getScaledFont("SansSerif", Font.BOLD, 16));
        button.setForeground(PANEL_BACKGROUND);
        button.setBackground(SECONDARY_BLUE);
        button.setBorder(new EmptyBorder(getScaledSize(16), getScaledSize(32), getScaledSize(16), getScaledSize(32)));
        button.setFocusPainted(false);
        button.setCursor(new Cursor(Cursor.HAND_CURSOR));
        button.setOpaque(true);
        button.setBorderPainted(false);
        button.setRolloverEnabled(true);
        button.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                if (button.isEnabled()) {
                    button.setBackground(SECONDARY_BLUE.darker());
                }
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                if (button.isEnabled()) {
                    button.setBackground(SECONDARY_BLUE);
                }
            }
        });
        return button;
    }

    private JButton createSecondaryButton(String text) {
        JButton button = new JButton(text);
        button.setFont(getScaledFont("SansSerif", Font.PLAIN, 15));
        button.setForeground(SECONDARY_BLUE);
        button.setBackground(PANEL_BACKGROUND);
        button.setBorder(BorderFactory.createCompoundBorder(
                new LineBorder(SECONDARY_BLUE, 1, true),
                new EmptyBorder(getScaledSize(14), getScaledSize(24), getScaledSize(14), getScaledSize(24))));
        button.setFocusPainted(false);
        button.setCursor(new Cursor(Cursor.HAND_CURSOR));
        button.setOpaque(true);
        button.setRolloverEnabled(true);
        button.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                if (button.isEnabled()) {
                    button.setBackground(new Color(230, 240, 255));
                }
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                if (button.isEnabled()) {
                    button.setBackground(PANEL_BACKGROUND);
                }
            }
        });
        return button;
    }

    private JButton createDangerButton(String text) {
        JButton button = new JButton(text);
        button.setFont(getScaledFont("SansSerif", Font.PLAIN, 15));
        button.setForeground(PANEL_BACKGROUND);
        button.setBackground(DANGER_RED);
        button.setBorder(new EmptyBorder(getScaledSize(14), getScaledSize(24), getScaledSize(14), getScaledSize(24)));
        button.setFocusPainted(false);
        button.setCursor(new Cursor(Cursor.HAND_CURSOR));
        button.setOpaque(true);
        button.setBorderPainted(false);
        button.setRolloverEnabled(true);
        button.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                if (button.isEnabled()) {
                    button.setBackground(DANGER_RED.darker());
                }
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                if (button.isEnabled()) {
                    button.setBackground(DANGER_RED);
                }
            }
        });
        return button;
    }

    private JButton createSuccessButton(String text) {
        JButton button = new JButton(text);
        button.setFont(getScaledFont("SansSerif", Font.PLAIN, 15));
        button.setForeground(PANEL_BACKGROUND);
        button.setBackground(SUCCESS_GREEN);
        button.setBorder(new EmptyBorder(getScaledSize(14), getScaledSize(24), getScaledSize(14), getScaledSize(24)));
        button.setFocusPainted(false);
        button.setCursor(new Cursor(Cursor.HAND_CURSOR));
        button.setOpaque(true);
        button.setBorderPainted(false);
        button.setRolloverEnabled(true);
        button.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                if (button.isEnabled()) {
                    button.setBackground(SUCCESS_GREEN.darker());
                }
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                if (button.isEnabled()) {
                    button.setBackground(SUCCESS_GREEN);
                }
            }
        });
        return button;
    }

    private void initializeService() {
        try {
            UserService service = new UserService();
            port = service.getUserServicePort();
            showMessage("✅ Service SOAP initialisé avec succès.\n📝 Veuillez vous connecter pour continuer.\n", SUCCESS_GREEN);
        } catch (Exception e) {
            showMessage("❌ Erreur lors de l'initialisation du service: " + e.getMessage() + "\n", DANGER_RED);
        }
    }

    private void authenticateUser() {
        String login = loginField.getText().trim();
        String password = new String(passwordField.getPassword());
        if (login.isEmpty() || password.isEmpty()) {
            showMessage("⚠️ Veuillez saisir un login et un mot de passe.", WARNING_ORANGE);
            return;
        }
        loginButton.setEnabled(false);
        loginButton.setText("🔄 Connexion en cours...");
        try {
            AuthenticateUserRequest request = new AuthenticateUserRequest();
            request.setLogin(login);
            request.setMotDePasse(password);
            AuthenticateUserResponse response = port.authenticateUser(request);
            token = response.getJeton();
            userLogin = login;
            if (token == null || token.trim().isEmpty()) {
                showMessage("⛔ Vous n'avez pas de jeton d'authentification. Demandez à un administrateur de vous en générer un via le site web.", DANGER_RED);
                return;
            }
            try {
                ListUsersRequest listReq = new ListUsersRequest();
                listReq.setJeton(token);
                ListUsersResponse listResp = port.listUsers(listReq);
                List<User> users = listResp.getUsers().getUser();
                User currentUser = users.stream()
                    .filter(u -> u.getLogin().equals(userLogin))
                    .findFirst()
                    .orElse(null);
                if (currentUser != null) {
                    userRole = currentUser.getRole();
                    if ("admin".equalsIgnoreCase(userRole)) {
                        showMessage("✅ Authentification réussie !\n🔑 Token: " + token + "\n👤 Utilisateur: " + login, SUCCESS_GREEN);
                        showAdminInterface();
                    } else {
                        showMessage("⛔ Accès refusé : seuls les administrateurs peuvent accéder à la gestion des utilisateurs.\nVotre rôle : " + userRole, DANGER_RED);
                        return;
                    }
                } else {
                    showMessage("❌ Utilisateur non trouvé après authentification.", DANGER_RED);
                    return;
                }
            } catch (Exception ex) {
                showMessage("❌ Erreur lors de la récupération du rôle utilisateur: " + ex.getMessage(), DANGER_RED);
            }
        } catch (Exception e) {
            showMessage("❌ Erreur lors de l'authentification: " + e.getMessage(), DANGER_RED);
        } finally {
            loginButton.setEnabled(true);
            loginButton.setText("🚀 Se connecter");
        }
    }

    private void showMessage(String message, Color color) {
        resultArea.setText(message + "\n");
        resultArea.setForeground(color);
        resultArea.setCaretPosition(0);
    }

    private void appendMessage(String message, Color color) {
        resultArea.setForeground(color);
        resultArea.append(message + "\n");
        resultArea.setCaretPosition(resultArea.getDocument().getLength());
    }

    private void showAdminInterface() {
        if (loginSectionPanel != null) {
            contentWrapperPanel.remove(loginSectionPanel);
            loginSectionPanel = null;
        }

        adminPanel = new JPanel(new BorderLayout());
        adminPanel.setBackground(PANEL_BACKGROUND);
        adminPanel.setBorder(BorderFactory.createCompoundBorder(
                new LineBorder(BORDER_GRAY, 1, true),
                new EmptyBorder(getScaledSize(30), getScaledSize(35), getScaledSize(30), getScaledSize(35))));
        adminPanel.setOpaque(true);

        JLabel adminTitle = new JLabel("👥 Panneau d'Administration");
        adminTitle.setFont(getScaledFont("SansSerif", Font.BOLD, 22));
        adminTitle.setForeground(TEXT_DARK);
        adminPanel.add(adminTitle, BorderLayout.NORTH);

        JPanel buttonPanel = new JPanel(new FlowLayout(FlowLayout.LEFT, getScaledSize(20), getScaledSize(25)));
        buttonPanel.setBackground(PANEL_BACKGROUND);

        JButton listUsersButton = createSecondaryButton("📋 Lister les utilisateurs");
        JButton addUserButton = createSuccessButton("➕ Ajouter un utilisateur");
        JButton updateUserButton = createSecondaryButton("✏️ Modifier un utilisateur");
        JButton deleteUserButton = createDangerButton("🗑️ Supprimer un utilisateur");
        JButton logoutButton = createSecondaryButton("🚪 Déconnexion");

        buttonPanel.add(listUsersButton);
        buttonPanel.add(addUserButton);
        buttonPanel.add(updateUserButton);
        buttonPanel.add(deleteUserButton);
        buttonPanel.add(logoutButton);

        adminPanel.add(buttonPanel, BorderLayout.SOUTH);

        listUsersButton.addActionListener(e -> listUsers());
        addUserButton.addActionListener(e -> showAddUserDialog());
        updateUserButton.addActionListener(e -> showUpdateUserDialog());
        deleteUserButton.addActionListener(e -> showDeleteUserDialog());
        logoutButton.addActionListener(e -> logout());

        contentWrapperPanel.add(adminPanel, BorderLayout.NORTH);
        contentWrapperPanel.revalidate();
        contentWrapperPanel.repaint();

        listUsers();
    }

    private boolean isTokenValid() {
        return token != null && !token.trim().isEmpty();
    }

    private void listUsers() {
        if (!isTokenValid()) {
            showMessage("⛔ Jeton d'authentification manquant ou expiré. Veuillez vous reconnecter.", DANGER_RED);
            logout();
            return;
        }
        try {
            ListUsersRequest request = new ListUsersRequest();
            request.setJeton(token);
            ListUsersResponse response = port.listUsers(request);
            List<User> users = response.getUsers().getUser();
            StringBuilder sb = new StringBuilder();
            sb.append("═══════════════════════════════════════════════════════════════\n");
            sb.append("                    📊 LISTE DES UTILISATEURS\n");
            sb.append("═══════════════════════════════════════════════════════════════\n\n");

            if (users.isEmpty()) {
                sb.append("ℹ️ Aucun utilisateur trouvé dans le système.\n");
                sb.append("💡 Utilisez le bouton 'Ajouter un utilisateur' pour créer le premier compte.\n");
            } else {
                for (int i = 0; i < users.size(); i++) {
                    User user = users.get(i);
                    sb.append("┌─ Utilisateur #").append(i + 1).append(" ─────────────────────────────────────────\n");
                    sb.append("│ 🆔 ID: ").append(user.getId()).append("\n");
                    sb.append("│ 👤 Nom d'utilisateur: ").append(user.getLogin()).append("\n");
                    sb.append("│ 🎭 Rôle: ").append(getRoleWithIcon(user.getRole())).append("\n");
                    sb.append("└─────────────────────────────────────────────────────────────\n\n");
                }
                sb.append("📈 Total: ").append(users.size()).append(" utilisateur(s) dans le système\n");
            }
            resultArea.setText(sb.toString());
            resultArea.setForeground(TEXT_DARK);
        } catch (Exception e) {
            showMessage("❌ Erreur lors de la récupération des utilisateurs: " + e.getMessage(), DANGER_RED);
        }
    }

    private String getRoleWithIcon(String role) {
        switch (role.toLowerCase()) {
            case "admin": return "👑 " + role;
            case "editeur": return "✏️ " + role;
            case "visiteur": return "👁️ " + role;
            default: return "❓ " + role;
        }
    }

    private void showAddUserDialog() {
        if (!isTokenValid()) {
            showMessage("⛔ Jeton d'authentification manquant ou expiré. Veuillez vous reconnecter.", DANGER_RED);
            logout();
            return;
        }
        JDialog dialog = createStyledDialog("➕ Ajouter un nouvel utilisateur");
        JPanel contentPanel = new JPanel(new GridBagLayout());
        contentPanel.setBackground(PANEL_BACKGROUND);
        contentPanel.setBorder(new EmptyBorder(getScaledSize(40), getScaledSize(40), getScaledSize(40), getScaledSize(40)));
        GridBagConstraints gbc = new GridBagConstraints();
        gbc.insets = new Insets(getScaledSize(20), getScaledSize(20), getScaledSize(20), getScaledSize(20));

        JLabel dialogTitle = new JLabel("👤 Création d'un nouveau compte utilisateur");
        dialogTitle.setFont(getScaledFont("SansSerif", Font.BOLD, 20));
        dialogTitle.setForeground(TEXT_DARK);
        gbc.gridx = 0; gbc.gridy = 0; gbc.gridwidth = 2; gbc.anchor = GridBagConstraints.CENTER;
        contentPanel.add(dialogTitle, gbc);

        gbc.gridwidth = 1;
        JTextField usernameField = createStyledTextField();
        usernameField.setToolTipText("Nom d'utilisateur unique (3-20 caractères)");
        JPasswordField passwordField = createStyledPasswordField();
        passwordField.setToolTipText("Mot de passe sécurisé (minimum 6 caractères)");
        JComboBox<String> roleCombo = createStyledComboBox(new String[]{"visiteur", "editeur", "admin"});
        roleCombo.setToolTipText("Sélectionnez le niveau d'accès");

        addFormField(contentPanel, "👤 Nom d'utilisateur:", usernameField, gbc, 1);
        addFormField(contentPanel, "🔒 Mot de passe:", passwordField, gbc, 2);
        addFormField(contentPanel, "🎭 Rôle:", roleCombo, gbc, 3);

        JPanel buttonPanel = new JPanel(new FlowLayout(FlowLayout.CENTER, getScaledSize(25), 0));
        buttonPanel.setBackground(PANEL_BACKGROUND);
        JButton addButton = createSuccessButton("✅ Créer l'utilisateur");
        JButton cancelButton = createSecondaryButton("❌ Annuler");
        buttonPanel.add(addButton);
        buttonPanel.add(cancelButton);

        gbc.gridx = 0; gbc.gridy = 4; gbc.gridwidth = 2; gbc.fill = GridBagConstraints.HORIZONTAL;
        gbc.insets = new Insets(getScaledSize(35), 0, 0, 0);
        contentPanel.add(buttonPanel, gbc);

        dialog.add(contentPanel);
        dialog.pack();
        dialog.setMinimumSize(new Dimension(getScaledSize(500), getScaledSize(400)));

        addButton.addActionListener(e -> {
            String username = usernameField.getText().trim();
            String password = new String(passwordField.getPassword());
            String role = (String) roleCombo.getSelectedItem();
            if (username.isEmpty() || password.isEmpty()) {
                JOptionPane.showMessageDialog(dialog,
                        "⚠️ Tous les champs sont obligatoires!",
                        "Validation",
                        JOptionPane.WARNING_MESSAGE);
                return;
            }
            if (username.length() < 3 || username.length() > 20) {
                JOptionPane.showMessageDialog(dialog,
                        "⚠️ Le nom d'utilisateur doit contenir entre 3 et 20 caractères!",
                        "Validation",
                        JOptionPane.WARNING_MESSAGE);
                return;
            }
            if (password.length() < 6) {
                JOptionPane.showMessageDialog(dialog,
                        "⚠️ Le mot de passe doit contenir au minimum 6 caractères!",
                        "Validation",
                        JOptionPane.WARNING_MESSAGE);
                return;
            }
            addButton.setEnabled(false);
            addButton.setText("🔄 Création en cours...");
            try {
                AddUserRequest request = new AddUserRequest();
                request.setJeton(token);
                request.setLogin(username);
                request.setMotDePasse(password);
                request.setRole(role);
                AddUserResponse response = port.addUser(request);
                if (response.isStatus()) {
                    showMessage("✅ Utilisateur '" + username + "' créé avec succès !\n🎭 Rôle: " + getRoleWithIcon(role), SUCCESS_GREEN);
                    dialog.dispose();
                    listUsers();
                } else {
                    showMessage("❌ Erreur lors de la création de l'utilisateur '" + username + "'.\n💡 Vérifiez que le nom d'utilisateur n'existe pas déjà.", DANGER_RED);
                }
            } catch (Exception ex) {
                showMessage("❌ Erreur technique: " + ex.getMessage(), DANGER_RED);
            } finally {
                addButton.setEnabled(true);
                addButton.setText("✅ Créer l'utilisateur");
            }
        });
        cancelButton.addActionListener(e -> dialog.dispose());
        dialog.getRootPane().setDefaultButton(addButton);
        usernameField.requestFocusInWindow();
        dialog.setVisible(true);
    }

    private JDialog createStyledDialog(String title) {
        JDialog dialog = new JDialog(frame, title, true);
        dialog.setLocationRelativeTo(frame);
        dialog.getContentPane().setBackground(PANEL_BACKGROUND);
        dialog.setResizable(true);
        return dialog;
    }

    private JComboBox<String> createStyledComboBox(String[] items) {
        JComboBox<String> combo = new JComboBox<>(items);
        combo.setFont(getScaledFont("SansSerif", Font.PLAIN, 16));
        combo.setBorder(BorderFactory.createCompoundBorder(
                new LineBorder(BORDER_GRAY, 1, true),
                new EmptyBorder(getScaledSize(12), getScaledSize(18), getScaledSize(12), getScaledSize(18))));
        combo.setBackground(PANEL_BACKGROUND);
        return combo;
    }

    private void addFormField(JPanel panel, String labelText, JComponent field, GridBagConstraints gbc, int row) {
        JLabel label = createStyledLabel(labelText);
        gbc.gridx = 0; gbc.gridy = row; gbc.anchor = GridBagConstraints.WEST; gbc.fill = GridBagConstraints.NONE; gbc.weightx = 0;
        panel.add(label, gbc);
        gbc.gridx = 1; gbc.fill = GridBagConstraints.HORIZONTAL; gbc.weightx = 1.0;
        panel.add(field, gbc);
    }

    private void showUpdateUserDialog() {
        if (!isTokenValid()) {
            showMessage("⛔ Jeton d'authentification manquant ou expiré. Veuillez vous reconnecter.", DANGER_RED);
            logout();
            return;
        }
        try {
            ListUsersRequest request = new ListUsersRequest();
            request.setJeton(token);
            ListUsersResponse response = port.listUsers(request);
            List<User> users = response.getUsers().getUser();
            if (users.isEmpty()) {
                showMessage("ℹ️ Aucun utilisateur à modifier dans le système.", WARNING_ORANGE);
                return;
            }
            String[] userOptions = users.stream()
                    .map(u -> u.getId() + " - " + u.getLogin())
                    .toArray(String[]::new);
            String selected = (String) JOptionPane.showInputDialog(
                    frame,
                    "Sélectionnez l'utilisateur à modifier:",
                    "Modifier un utilisateur",
                    JOptionPane.QUESTION_MESSAGE,
                    null,
                    userOptions,
                    userOptions[0]
            );
            if (selected != null) {
                int userId = Integer.parseInt(selected.split(" - ")[0]);
                User userToUpdate = users.stream()
                        .filter(u -> u.getId() == userId)
                        .findFirst()
                        .orElse(null);
                if (userToUpdate != null) {
                    showUpdateUserForm(userToUpdate);
                }
            }
        } catch (Exception e) {
            showMessage("❌ Erreur lors de la récupération des utilisateurs: " + e.getMessage(), DANGER_RED);
        }
    }

    private void showUpdateUserForm(User user) {
        JDialog dialog = createStyledDialog("✏️ Modifier l'utilisateur: " + user.getLogin());
        JPanel contentPanel = new JPanel(new GridBagLayout());
        contentPanel.setBackground(PANEL_BACKGROUND);
        contentPanel.setBorder(new EmptyBorder(getScaledSize(40), getScaledSize(40), getScaledSize(40), getScaledSize(40)));
        GridBagConstraints gbc = new GridBagConstraints();
        gbc.insets = new Insets(getScaledSize(20), getScaledSize(20), getScaledSize(20), getScaledSize(20));

        JLabel dialogTitle = new JLabel("✏️ Modification du compte utilisateur");
        dialogTitle.setFont(getScaledFont("SansSerif", Font.BOLD, 20));
        dialogTitle.setForeground(TEXT_DARK);
        gbc.gridx = 0; gbc.gridy = 0; gbc.gridwidth = 2; gbc.anchor = GridBagConstraints.CENTER;
        contentPanel.add(dialogTitle, gbc);

        JLabel userInfo = new JLabel(String.format("🆔 ID: %d | 👤 Utilisateur actuel: %s", user.getId(), user.getLogin()));
        userInfo.setFont(getScaledFont("SansSerif", Font.PLAIN, 14));
        userInfo.setForeground(TEXT_LIGHT);
        gbc.gridy = 1;
        contentPanel.add(userInfo, gbc);

        gbc.gridwidth = 1;
        JTextField usernameField = createStyledTextField();
        usernameField.setText(user.getLogin());
        usernameField.setToolTipText("Nouveau nom d'utilisateur");
        JPasswordField passwordField = createStyledPasswordField();
        passwordField.setToolTipText("Nouveau mot de passe (laisser vide pour conserver l'actuel)");
        JComboBox<String> roleCombo = createStyledComboBox(new String[]{"visiteur", "editeur", "admin"});
        roleCombo.setSelectedItem(user.getRole());
        roleCombo.setToolTipText("Nouveau rôle");

        addFormField(contentPanel, "👤 Nom d'utilisateur:", usernameField, gbc, 2);
        addFormField(contentPanel, "🔒 Nouveau mot de passe:", passwordField, gbc, 3);
        JLabel passwordHint = new JLabel("💡 Laisser vide pour conserver le mot de passe actuel");
        passwordHint.setFont(getScaledFont("SansSerif", Font.ITALIC, 13));
        passwordHint.setForeground(TEXT_LIGHT);
        gbc.gridx = 1; gbc.gridy = 4; gbc.anchor = GridBagConstraints.WEST;
        contentPanel.add(passwordHint, gbc);
        addFormField(contentPanel, "🎭 Rôle:", roleCombo, gbc, 5);

        JPanel buttonPanel = new JPanel(new FlowLayout(FlowLayout.CENTER, getScaledSize(25), 0));
        buttonPanel.setBackground(PANEL_BACKGROUND);
        JButton updateButton = createPrimaryButton("💾 Sauvegarder les modifications");
        JButton cancelButton = createSecondaryButton("❌ Annuler");
        buttonPanel.add(updateButton);
        buttonPanel.add(cancelButton);

        gbc.gridx = 0; gbc.gridy = 6; gbc.gridwidth = 2; gbc.fill = GridBagConstraints.HORIZONTAL;
        gbc.insets = new Insets(getScaledSize(35), 0, 0, 0);
        contentPanel.add(buttonPanel, gbc);

        dialog.add(contentPanel);
        dialog.pack();
        dialog.setMinimumSize(new Dimension(getScaledSize(500), getScaledSize(450)));

        updateButton.addActionListener(e -> {
            String newUsername = usernameField.getText().trim();
            String newPassword = new String(passwordField.getPassword());
            String newRole = (String) roleCombo.getSelectedItem();
            if (newUsername.isEmpty()) {
                JOptionPane.showMessageDialog(dialog,
                        "⚠️ Le nom d'utilisateur ne peut pas être vide!",
                        "Validation",
                        JOptionPane.WARNING_MESSAGE);
                return;
            }
            if (newUsername.length() < 3 || newUsername.length() > 20) {
                JOptionPane.showMessageDialog(dialog,
                        "⚠️ Le nom d'utilisateur doit contenir entre 3 et 20 caractères!",
                        "Validation",
                        JOptionPane.WARNING_MESSAGE);
                return;
            }
            if (!newPassword.isEmpty() && newPassword.length() < 6) {
                JOptionPane.showMessageDialog(dialog,
                        "⚠️ Le nouveau mot de passe doit contenir au minimum 6 caractères!",
                        "Validation",
                        JOptionPane.WARNING_MESSAGE);
                return;
            }
            updateButton.setEnabled(false);
            updateButton.setText("🔄 Modification en cours...");
            try {
                UpdateUserRequest request = new UpdateUserRequest();
                request.setJeton(token);
                request.setId(user.getId());
                request.setLogin(newUsername);
                request.setRole(newRole);
                if (!newPassword.isEmpty()) {
                    request.setMotDePasse(newPassword);
                }
                UpdateUserResponse response = port.updateUser(request);
                if (response.isStatus()) {
                    String message = "✅ Utilisateur modifié avec succès !\n";
                    message += "👤 Nom: " + newUsername + "\n";
                    message += "🎭 Rôle: " + getRoleWithIcon(newRole);
                    if (!newPassword.isEmpty()) {
                        message += "\n🔒 Mot de passe mis à jour";
                    }
                    showMessage(message, SUCCESS_GREEN);
                    dialog.dispose();
                    listUsers();
                } else {
                    showMessage("❌ Erreur lors de la modification de l'utilisateur.\n💡 Vérifiez que le nom d'utilisateur n'est pas déjà utilisé.", DANGER_RED);
                }
            } catch (Exception ex) {
                showMessage("❌ Erreur technique: " + ex.getMessage(), DANGER_RED);
            } finally {
                updateButton.setEnabled(true);
                updateButton.setText("💾 Sauvegarder les modifications");
            }
        });
        cancelButton.addActionListener(e -> dialog.dispose());
        dialog.getRootPane().setDefaultButton(updateButton);
        usernameField.requestFocusInWindow();
        dialog.setVisible(true);
    }

    private void showDeleteUserDialog() {
        if (!isTokenValid()) {
            showMessage("⛔ Jeton d'authentification manquant ou expiré. Veuillez vous reconnecter.", DANGER_RED);
            logout();
            return;
        }
        try {
            ListUsersRequest request = new ListUsersRequest();
            request.setJeton(token);
            ListUsersResponse response = port.listUsers(request);
            List<User> users = response.getUsers().getUser();
            if (users.isEmpty()) {
                showMessage("ℹ️ Aucun utilisateur à supprimer dans le système.", WARNING_ORANGE);
                return;
            }
            String[] userOptions = users.stream()
                    .map(u -> u.getId() + " - " + u.getLogin())
                    .toArray(String[]::new);
            String selected = (String) JOptionPane.showInputDialog(
                    frame,
                    "Sélectionnez l'utilisateur à supprimer:",
                    "Supprimer un utilisateur",
                    JOptionPane.QUESTION_MESSAGE,
                    null,
                    userOptions,
                    userOptions[0]
            );
            if (selected != null) {
                int userId = Integer.parseInt(selected.split(" - ")[0]);
                int confirm = JOptionPane.showConfirmDialog(
                        frame,
                        "🚨 Êtes-vous sûr de vouloir supprimer cet utilisateur ?\nCette action est irréversible !",
                        "Confirmation de suppression",
                        JOptionPane.YES_NO_OPTION,
                        JOptionPane.WARNING_MESSAGE
                );
                if (confirm == JOptionPane.YES_OPTION) {
                    DeleteUserRequest deleteRequest = new DeleteUserRequest();
                    deleteRequest.setJeton(token);
                    deleteRequest.setId(userId);
                    DeleteUserResponse deleteResponse = port.deleteUser(deleteRequest);
                    if (deleteResponse.isStatus()) {
                        showMessage("✅ Utilisateur supprimé avec succès !", SUCCESS_GREEN);
                        listUsers();
                    } else {
                        showMessage("❌ Erreur lors de la suppression de l'utilisateur.", DANGER_RED);
                    }
                }
            }
        } catch (Exception e) {
            showMessage("❌ Erreur lors de la suppression: " + e.getMessage(), DANGER_RED);
        }
    }

    private void logout() {
        int confirm = JOptionPane.showConfirmDialog(
                frame,
                "🚪 Êtes-vous sûr de vouloir vous déconnecter ?",
                "Confirmation de déconnexion",
                JOptionPane.YES_NO_OPTION,
                JOptionPane.QUESTION_MESSAGE
        );
        if (confirm == JOptionPane.YES_OPTION) {
            token = null;
            if (adminPanel != null) {
                contentWrapperPanel.remove(adminPanel);
                adminPanel = null;
            }
            loginSectionPanel = createStyledLoginPanel();
            contentWrapperPanel.add(loginSectionPanel, BorderLayout.NORTH);

            loginField.setText("");
            passwordField.setText("");
            showMessage("🚪 Déconnexion réussie.\n📝 Merci d'avoir utilisé le système ESP Dakar.\n🔐 Veuillez vous reconnecter pour continuer.", SUCCESS_GREEN);

            contentWrapperPanel.revalidate();
            contentWrapperPanel.repaint();

            loginField.requestFocusInWindow();
        }
    }

    public static void main(String[] args) {
        SwingUtilities.invokeLater(() -> {
            try {
                for (UIManager.LookAndFeelInfo info : UIManager.getInstalledLookAndFeels()) {
                    if ("Nimbus".equals(info.getName())) {
                        UIManager.setLookAndFeel(info.getClassName());
                        break;
                    }
                }
                new UserManagementApp();
            } catch (Exception e) {
                e.printStackTrace();
                new UserManagementApp();
            }
        });
    }
}

