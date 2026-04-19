// =============================================
// VEDAWAYS — Node.js Backend Server
// Stack: Express + MongoDB (Mongoose)
// =============================================

const express = require("express");
const mongoose = require("mongoose");
const cors = require("cors");
const path = require("path");
const nodemailer = require("nodemailer");
require("dotenv").config();

const app = express();
const PORT = process.env.PORT || 5000;

app.use(express.static(path.join(__dirname, "")));
// ===== MIDDLEWARE =====
app.use(
  cors({
    origin: process.env.FRONTEND_URL || "http://localhost:3000",
    credentials: true,
  }),
);
app.use(express.json());
app.use(express.static(path.join(__dirname, "")));

// ===== MONGODB CONNECTION =====
mongoose
  .connect(process.env.MONGO_URI || "mongodb://localhost:27017/vedaways")
  .then(() => console.log("✅ MongoDB Connected"))
  .catch((err) => console.error("❌ MongoDB Error:", err));

// ===== MODELS =====

// Inquiry Model
const inquirySchema = new mongoose.Schema({
  name: { type: String, required: true },
  email: { type: String, required: true },
  phone: String,
  nationality: String,
  interest: String, // luxury, cultural, wildlife, spiritual, honeymoon, offbeat
  duration: String, // 5-7, 8-12, 12+
  budget: String, // budget range
  budget_range: String,
  itinerary: String, // selected itinerary ID if any
  message: String,
  status: {
    type: String,
    enum: ["new", "contacted", "converted", "closed"],
    default: "new",
  },
  source: { type: String, default: "website" },
  createdAt: { type: Date, default: Date.now },
});
const Inquiry = mongoose.model("Inquiry", inquirySchema);

// Contact Model
const contactSchema = new mongoose.Schema({
  name: String,
  email: String,
  phone: String,
  interest: String,
  message: String,
  createdAt: { type: Date, default: Date.now },
});
const Contact = mongoose.model("Contact", contactSchema);

// Itinerary Model
const itinerarySchema = new mongoose.Schema({
  id: { type: String, unique: true },
  title: String,
  subtitle: String,
  route: [String],
  duration: String,
  nights: Number,
  category: [String],
  highlights: [String],
  includes: [String],
  excludes: [String],
  priceMin: Number,
  priceMax: Number,
  heroImage: String,
  days: [
    {
      day: Number,
      title: String,
      location: String,
      activities: [String],
      hotel: String,
      meals: String,
    },
  ],
  addOns: [
    {
      name: String,
      price: Number,
      icon: String,
    },
  ],
  featured: { type: Boolean, default: false },
  active: { type: Boolean, default: true },
  createdAt: { type: Date, default: Date.now },
});
const Itinerary = mongoose.model("Itinerary", itinerarySchema);

// User Model (for saved trips & dashboard)
const userSchema = new mongoose.Schema({
  name: String,
  email: { type: String, unique: true },
  phone: String,
  nationality: String,
  preferences: {
    interests: [String],
    budget: String,
    duration: String,
    destinations: [String],
  },
  savedItineraries: [
    { type: mongoose.Schema.Types.ObjectId, ref: "Itinerary" },
  ],
  createdAt: { type: Date, default: Date.now },
});
const User = mongoose.model("User", userSchema);

// ===== EMAIL SETUP =====
const transporter = nodemailer.createTransport({
  service: "gmail",
  auth: {
    user: process.env.EMAIL_USER,
    pass: process.env.EMAIL_PASS,
  },
});

async function sendNotificationEmail(subject, htmlContent) {
  if (!process.env.EMAIL_USER) return;
  try {
    await transporter.sendMail({
      from: `"VedaWays" <${process.env.EMAIL_USER}>`,
      to: process.env.ADMIN_EMAIL || process.env.EMAIL_USER,
      subject,
      html: htmlContent,
    });
  } catch (err) {
    console.error("Email error:", err.message);
  }
}

// ===== ROUTES =====

// Health check
app.get("/api/health", (req, res) => {
  res.json({ status: "ok", message: "VedaWays API running 🚀" });
});

// --- INQUIRY ROUTES ---

// POST /api/inquiry — Plan My Trip form
app.post("/api/inquiry", async (req, res) => {
  try {
    const inquiry = new Inquiry(req.body);
    await inquiry.save();

    // Send email notification
    await sendNotificationEmail(
      `🌟 New Trip Inquiry — ${req.body.name}`,
      `
      <h2>New Inquiry from VedaWays</h2>
      <table>
        <tr><td><b>Name:</b></td><td>${req.body.name}</td></tr>
        <tr><td><b>Email:</b></td><td>${req.body.email}</td></tr>
        <tr><td><b>Phone/WhatsApp:</b></td><td>${req.body.phone || "N/A"}</td></tr>
        <tr><td><b>Nationality:</b></td><td>${req.body.nationality || "N/A"}</td></tr>
        <tr><td><b>Interest:</b></td><td>${req.body.interest}</td></tr>
        <tr><td><b>Duration:</b></td><td>${req.body.duration} days</td></tr>
        <tr><td><b>Budget:</b></td><td>₹${parseInt(req.body.budget).toLocaleString("en-IN")}</td></tr>
      </table>
      `,
    );

    res
      .status(201)
      .json({ success: true, message: "Inquiry received!", id: inquiry._id });
  } catch (err) {
    res.status(500).json({ success: false, error: err.message });
  }
});

// GET /api/inquiries — Admin: get all inquiries
app.get("/api/inquiries", async (req, res) => {
  try {
    const { status, page = 1, limit = 20 } = req.query;
    const filter = status ? { status } : {};
    const inquiries = await Inquiry.find(filter)
      .sort({ createdAt: -1 })
      .limit(limit * 1)
      .skip((page - 1) * limit);
    const total = await Inquiry.countDocuments(filter);
    res.json({ inquiries, total, pages: Math.ceil(total / limit) });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// PATCH /api/inquiries/:id — Update inquiry status
app.patch("/api/inquiries/:id", async (req, res) => {
  try {
    const inquiry = await Inquiry.findByIdAndUpdate(req.params.id, req.body, {
      new: true,
    });
    res.json(inquiry);
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// --- CONTACT ROUTES ---

// POST /api/contact — Contact form
app.post("/api/contact", async (req, res) => {
  try {
    const contact = new Contact(req.body);
    await contact.save();

    await sendNotificationEmail(
      `📬 Contact Form — ${req.body.name}`,
      `
      <h2>New Contact Message — VedaWays</h2>
      <p><b>Name:</b> ${req.body.name}</p>
      <p><b>Email:</b> ${req.body.email}</p>
      <p><b>Phone:</b> ${req.body.phone || "N/A"}</p>
      <p><b>Interest:</b> ${req.body.interest || "N/A"}</p>
      <p><b>Message:</b><br/>${req.body.message}</p>
      `,
    );

    res.status(201).json({ success: true, message: "Message received!" });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// --- ITINERARY ROUTES ---

// GET /api/itineraries — Get all active itineraries
app.get("/api/itineraries", async (req, res) => {
  try {
    const { category, minPrice, maxPrice, duration } = req.query;
    const filter = { active: true };
    if (category) filter.category = { $in: [category] };
    if (minPrice || maxPrice) {
      filter.priceMin = {};
      if (minPrice) filter.priceMin.$gte = parseInt(minPrice);
      if (maxPrice) filter.priceMin.$lte = parseInt(maxPrice);
    }
    const itineraries = await Itinerary.find(filter).sort({
      featured: -1,
      createdAt: -1,
    });
    res.json(itineraries);
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// GET /api/itineraries/:id — Get single itinerary
app.get("/api/itineraries/:id", async (req, res) => {
  try {
    const itinerary = await Itinerary.findOne({
      id: req.params.id,
      active: true,
    });
    if (!itinerary)
      return res.status(404).json({ error: "Itinerary not found" });
    res.json(itinerary);
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// POST /api/itineraries — Admin: create itinerary
app.post("/api/itineraries", async (req, res) => {
  try {
    const itinerary = new Itinerary(req.body);
    await itinerary.save();
    res.status(201).json(itinerary);
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// PUT /api/itineraries/:id — Admin: update itinerary
app.put("/api/itineraries/:id", async (req, res) => {
  try {
    const itinerary = await Itinerary.findOneAndUpdate(
      { id: req.params.id },
      req.body,
      { new: true },
    );
    res.json(itinerary);
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// --- USER ROUTES ---

// POST /api/users — Create/find user (after login)
app.post("/api/users", async (req, res) => {
  try {
    let user = await User.findOne({ email: req.body.email });
    if (!user) {
      user = new User(req.body);
      await user.save();
    }
    res.json(user);
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// PATCH /api/users/:id/preferences — Save user preferences
app.patch("/api/users/:id/preferences", async (req, res) => {
  try {
    const user = await User.findByIdAndUpdate(
      req.params.id,
      { preferences: req.body },
      { new: true },
    );
    res.json(user);
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// POST /api/users/:id/save-itinerary — Save itinerary to user profile
app.post("/api/users/:id/save-itinerary", async (req, res) => {
  try {
    const user = await User.findByIdAndUpdate(
      req.params.id,
      { $addToSet: { savedItineraries: req.body.itineraryId } },
      { new: true },
    ).populate("savedItineraries");
    res.json(user);
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// --- NEWSLETTER ---
app.post("/api/newsletter", async (req, res) => {
  try {
    const { email } = req.body;
    await sendNotificationEmail(
      "📧 New Newsletter Subscriber",
      `<p>New subscriber: <b>${email}</b></p>`,
    );
    res.json({ success: true });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// ===== SERVE FRONTEND =====
app.get("*", (req, res) => {
  res.sendFile(path.join(__dirname, "./index.html"));
});

// ===== START SERVER =====
app.listen(PORT, () => {
  console.log(`🚀 VedaWays Server running on http://localhost:${PORT}`);
  console.log(`📦 Environment: ${process.env.NODE_ENV || "development"}`);
});

module.exports = app;
